<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
    ],
    'controllerNamespace' => 'console\controllers',
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
//                    'levels' => ['error', 'warning'],
//                    'logFile' => '@app/ common/rbac/app.log',
                ],
            ],
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\User',
            'enableSession' => false,
            'enableAutoLogin' => false,
//            'on afterLogin' =>
//                function (\yii\web\UserEvent $event) {
//                    /** @var \common\models\User $user * */
//                    $user = $event->identity;
//                    echo 'Пользователь ' . $user->username . ' подключился!' . PHP_EOL;
//                }
        ],
    ],
    'params' => $params,
];
