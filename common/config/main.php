<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => [
//        'log',
        'common\components\ShopNotificator',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            //зададим куда будут сохраняться наши файлы конфигураций RBAC
//            'itemFile' => '@common/rbac/items.php',
//            'assignmentFile' => '@common/rbac/assignments.php',
//            'ruleFile' => '@common/rbac/rules.php'
        ],
/*        'log' => [
            'traceLevel' => 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'logFile' => '@app/runtime/logs/app.log',
                    'logVars' => [],
                    'categories' => ['yii\web\User::login', 'application'],
                ],
            ],
        ], */
    ],
];
