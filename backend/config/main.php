<?php

$params = array_merge(
        require __DIR__ . '/../../common/config/params.php', require __DIR__ . '/../../common/config/params-local.php', require __DIR__ . '/params.php', require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'name' => '绘本管理平台',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\AdminUser',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>s' => '<controller>/index',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
            ],
        ],
        'authManager' => [
            'class' => 'common\modules\rbac\RbacManager',
            'cache' => [
                'class' => 'yii\caching\FileCache',
                'cachePath' => dirname(dirname(__DIR__)) . '/frontend/runtime/cache'
            ]
        ],
    ],
    'params' => $params,
    'modules' => [
        //权限控制
        'rbac' => [
            'class' => 'common\modules\rbac\Module',
        ],
        //权限控制
        'user_admin' => [
            'class' => 'backend\modules\user_admin\Module',
        ],
        //系统管理
        'system_admin' => [
            'class' => 'backend\modules\system_admin\Module',
        ],
        //素材管理
        'goods_admin' => [
            'class' => 'backend\modules\goods_admin\Module',
        ],
        //素材配置
        'platform_config' => [
            'class' => 'backend\modules\platform_config\Module',
        ],
        //订单管理
        'order_admin' => [
            'class' => 'backend\modules\order_admin\Module',
        ],
        //统计
        'statistics' => [
            'class' => 'backend\modules\statistics\Module',
        ],
        'platform_admin' => [
            'class' => 'backend\modules\platform_admin\Module',
        ],
        //reids缓存管理
        'rediscache_admin' => [
            'class' => 'backend\modules\rediscache_admin\Module',
        ],
    ],
    'params' => $params,
    'as access' => [
        'class' => 'common\modules\rbac\components\AccessControl',
        'allowActions' => [
            'site/*',
            'webuploader/*',
            'ueditor/*',
            'external/*',
            'gii/*',
            'debug/*',
            'system_admin/region/*',
        // The actions listed here will be allowed to everyone including guests.
        // So, 'admin/*' should not appear here in the production, of course.
        // But in the earlier stages of your development, you may probably want to
        // add a lot of actions here until you finally completed setting up rbac,
        // otherwise you may not even take a first step.
        ]
    ],
];
