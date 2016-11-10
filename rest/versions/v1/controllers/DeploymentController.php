<?php
namespace rest\versions\v1\controllers;

use common\models\Account;
use common\models\User;
use Google_Service_Sheets_ValueRange;
use Yii;
use common\models\Deployment;
use rest\overrides\ActiveController;
use yii\web\ForbiddenHttpException;
use linslin\yii2\curl;
use yii\helpers\ArrayHelper;

/**
 * Class UserController
 * @package rest\versions\v1\controllers
 */
class DeploymentController extends ActiveController
{
    public $modelClass = 'common\models\Deployment';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = [
            'options',
            'github'
        ];

        return $behaviors;
    }

    public function actionGithub()
    {
        $owner = Yii::$app->request->post('owner');
        $repo = Yii::$app->request->post('repo');

        //$res = $this->saveDataToSheetsGoogle([]);
        //return $res;

        if (!$owner && !$repo) {
            throw new ForbiddenHttpException('Forbidden error.');
        }

        $stargazers = $this->getStargazers($owner, $repo);

        if (!$stargazers) {
            return [];
        } elseif (isset($stargazers->message)) {
            return $stargazers;
        }

        $users = [
            [
                'name',
                'followers',
                'blog',
                'email'
            ]
        ];
        foreach ($stargazers as $startgazer) {
            $owner = $startgazer->login;
            $user = $this->getUser($owner);

            if (!$user) {
                continue;
            }

            if (!$user->email) {
                $user->email = $this->getEmail($owner);
            }

            $data = [
                (string) $user->name,
                (string) $user->followers,
                (string) $user->blog,
                (string) $user->email,
            ];

            $users[] = $data;
        }

        $result = $this->saveDataToSheetsGoogle($users);

        return $result;
    }

    private function getDataGithubAPI($url)
    {
        $curl = new curl\Curl();
        $headers = [
            'Authorization: Basic a2thYWJiYWE6N2sxMW1mNno=',
        ];
        $response = $curl
            ->setOption(CURLOPT_HTTPHEADER, $headers)
            ->get($url);
        $data = json_decode($response);

        return $data;
    }

    private function getStargazers($owner, $repo)
    {
        $url = "https://api.github.com/repos/{$owner}/{$repo}/stargazers";
        $stargazers = $this->getDataGithubAPI($url);

        return $stargazers;
    }

    private function getUser($owner)
    {
        $url = "https://api.github.com/users/{$owner}";
        $user = $this->getDataGithubAPI($url);

        return $user;
    }

    private function getEmail($owner)
    {
        $url = "https://api.github.com/users/{$owner}/events/public";
        $event = $this->getDataGithubAPI($url);

        if (
            isset($event[0]) && $event[0]
            && isset($event[0]->payload) && $event[0]->payload
            && isset($event[0]->payload->commits[0]) && $event[0]->payload->commits[0]
            && isset($event[0]->payload->commits[0]->author) && $event[0]->payload->commits[0]->author
            && isset($event[0]->payload->commits[0]->author->email) && $event[0]->payload->commits[0]->author->email
        ) {
            $email = $event[0]->payload->commits[0]->author->email;
            return $email;
        } else {
            return null;
        }
    }

    private function saveDataToSheetsGoogle($values = [])
    {
        //if(!$values) {
        //    return false;
        //}
        /**
         * @var $service \Google_Service_Sheets
         */
        $service = Yii::$app->sheets->getService();
        $spreadsheetId = '15mcuMzJGWdyflUaU7YHmjQd3KmXaImIBt37WQEaRvME';
        $range = 'A1';

        //$values = [
        //    [
        //        'aaa',
        //        'test2',
        //    ],
        //    [
        //        'asdasd',
        //        'gfdfdgdgf',
        //    ],
        //    // Additional rows ...
        //];

        $body = new Google_Service_Sheets_ValueRange(array(
            'values' => $values
        ));

        $params = array(
            'valueInputOption' => 'RAW'
        );

        $result = $service->spreadsheets_values->update($spreadsheetId, $range,
            $body, $params);

        return $result;
    }

}
