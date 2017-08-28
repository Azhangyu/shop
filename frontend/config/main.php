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
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute' => 'user/login',
    'language' => 'zh-CN',
    'layout'=>false,
    'components' => [
        'request' => [
            'csrfParam' => '_csrf',
        ],
        'user' => [
            //user组件 配置到自己的登陆页面
            'identityClass' => 'frontend\models\member',
            'enableAutoLogin' => true,
//            'loginUrl'=>null,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        //阿里大于短信验证码配置
        'sms'=>[
            'class'=>\frontend\components\Sms::className(),
            'ak'=>'LTAI3kwthNeiEmwF',
            'sk'=>'iWjO4jedFAyPbBzO29usHBlpM60CXg',
            'sign'=>'张渝的个人店铺',
            'template'=>'SMS_87490001'
        ]
    ],
    'params' => $params,
];
