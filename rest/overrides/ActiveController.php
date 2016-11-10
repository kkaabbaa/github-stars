<?php

namespace rest\overrides;

use Yii;
use yii\rest\ActiveController as BaseActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\RateLimiter;
use yii\filters\auth\QueryParamAuth;
use yii\filters\auth\HttpBearerAuth;

class ActiveController extends BaseActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['corsFilter']['class'] = \yii\filters\Cors::className();
        $behaviors['corsFilter']['cors']['Access-Control-Expose-Headers'] = [
            'X-Pagination-Current-Page',
            'X-Pagination-Page-Count',
            'X-Pagination-Per-Page',
            'X-Pagination-Total-Count',
        ];

        $behaviors['authenticator']['class'] = CompositeAuth::className();
        $behaviors['authenticator']['authMethods'] = [QueryParamAuth::className(), HttpBearerAuth::className()];

        $behaviors['rateLimiter']['class'] = RateLimiter::className();
        $behaviors['rateLimiter']['enableRateLimitHeaders'] = false;
        $behaviors['rateLimiter']['errorMessage'] = 'Too Many Requests';
        unset($behaviors['rateLimiter']);

        return $behaviors;
    }

    /**
    * @inheritdoc
    */
    public function actions()
    {
        $actions = parent::actions();
        $actions['updateAll'] = [
            'class' => 'rest\overrides\UpdateAllAction',
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
            'scenario' => $this->updateScenario,
        ];
        return $actions;
    }


    public function verbs()
    {
        $verbs = parent::verbs();
        $verbs['update'] = ['PUT', 'PATCH', 'POST'];
        $verbs['updateAll'] = ['PUT', 'PATCH', 'POST'];

        return $verbs;
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
//            if (isset(Yii::$app->request->headers['authorization'])) {
//                $queryParamAuth = new HttpBearerAuth;
//                $queryParamAuth->authenticate(Yii::$app->user, Yii::$app->request, Yii::$app->response);
//            }
            return true;
        }

        return false;
    }
}
