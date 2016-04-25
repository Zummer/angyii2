<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'name' => 'Мой сайт',
    'id' => 'app-frontend',
    'bootstrap' => ['log'],
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'log' => [
//            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories' => ['shopNotifier'], //'yii\web\User::login',
                    'logVars' => [],
                    'logFile' => '@app/runtime/logs/info.log',
                ],
            ],
        ],
        'user' => [
            'class' => 'yii\web\User', // возможно
            'identityClass' => 'common\models\User',
            'enableSession' => false,
            'loginUrl' => null,
            'enableAutoLogin' => false,
//            Записал на всякий случай интересную возможность навесить обработчик на событие
//            'on afterLogin' =>
//                function (\yii\web\UserEvent $event) {
//                    /** @var common\models\User $user **/
//                    $user = $event->identity;
//                    $user->updateAttributes(['logged_at' => time()]);
//            }
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
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => ['user', 'restpost'],], //'pluralize' => false // отключаем S в конце слова
                'POST restposts/update/<id>' => 'restpost/update',
                'POST restposts/delete/<id>' => 'restpost/delete',
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
