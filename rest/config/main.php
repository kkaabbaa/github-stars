<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'rest-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'rest\versions\v1\RestModule'
        ],
        'v2' => [
            'class' => 'rest\versions\v2\RestModule'
        ],
    ],
    'components' => [
        'sheets' => [
            'class' => 'machour\yii2\google\apiclient\components\GoogleApiClient',
            'credentialsPath' => '@runtime/google-apiclient/sheets_a5e0b6bc-c73b-42ae-9c42-5312b3fb2946.json',
            'clientSecretPath' => '@runtime/google-apiclient/client_id.json',
            'api' => Google_Service_Sheets::class,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableSession' => false,
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories' => ['deployment'],
                    'logFile' => '@app/runtime/logs/info/deployment-logs.log',
                ],
            ],
        ],
        'request' => [
            'class' => '\yii\web\Request',
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'v1/deployment'
                    ]
                ],

                'OPTIONS v1/user/login' => 'v1/user/login',
                'POST v1/user/login' => 'v1/user/login',

                'OPTIONS v1/deployment/github' => 'v1/deployment/github',
                'POST v1/deployment/github' => 'v1/deployment/github',
            ],
        ],
    ],
    'params' => $params,
];
