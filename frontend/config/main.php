<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'homeUrl' => '/',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            'name' => 'advanced-frontend',
            'savePath' => 'C:/xampp/htdocs/test-advanced-template/frontend/runtime/session'
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                '<controller:\w+>/<id:\d+>'=>'<controller>/show',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                [
                    'pattern' => '<action:(about|contact|signup|login|logout|cabinet)>',
                    'route' => 'site/<action>',                    
                ]
            ],
        ], 
        'assetManager' => [
            'appendTimestamp' => true
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            /*'httpClient' => [
                'transport' => 'yii\httpclient\CurlTransport',
            ],*/
            'clients' => [
                'vkontakte' => [
                    'class' => 'yii\authclient\clients\VKontakte',
                    'clientId' => '5657839',
                    'clientSecret' => 'Bd2nmBrVTNTNR5dfLPaH'                    
                ],
            ],
        ],        
    ],
    'params' => $params,
];
