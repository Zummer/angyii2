<?php

namespace console\controllers;

use common\models\Post;
use common\models\User;
use common\rbac\AuthorRule;
use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

/**
 * RBAC generator
 */
class RbacController extends Controller
{
    /**
     * Generates roles
     */

    public function actionDelall()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
    }

    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
        // Create Post
        $createPost = $auth->createPermission('createPost');
        $createPost->description = 'Create a post';
        $auth->add($createPost);
        // Update Post
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update that post';
        $auth->add($updatePost);
        // Read/View Post
        $viewPost = $auth->createPermission('viewPost');
        $viewPost->description = 'View that post';
        $auth->add($viewPost);
        // Delete Post
        $deletePost = $auth->createPermission('deletePost');
        $deletePost->description = 'Delete that post';
        $auth->add($deletePost);

        $authorRule = new AuthorRule();
        $auth->add($authorRule);

        $updateOwnPost = $auth->createPermission('updateOwnPost');
        $updateOwnPost->description = 'Update own post';
        $updateOwnPost->ruleName = $authorRule->name;
        $auth->add($updateOwnPost);
        $auth->addChild($updateOwnPost, $updatePost);

        $viewOwnPost = $auth->createPermission('viewOwnPost');
        $viewOwnPost->description = 'View own post';
        $viewOwnPost->ruleName = $authorRule->name;
        $auth->add($viewOwnPost);
        $auth->addChild($viewOwnPost, $viewPost);

        $user = $auth->createRole('user');
        $user->description = 'User';
        $auth->add($user);

        $auth->addChild($user, $createPost);
        $auth->addChild($user, $updateOwnPost);
        $auth->addChild($user, $viewOwnPost);

        $admin = $auth->createRole('admin');
        $admin->description = 'Admin';
        $auth->add($admin);

        $auth->addChild($admin, $user);
        $auth->addChild($admin, $viewPost);
        $auth->addChild($admin, $updatePost);
        $auth->addChild($admin, $deletePost);

        $this->stdout('RBAC создан!' . PHP_EOL);

        $this->actionAssign();
    }

    public function actionAssign()
    {
        $auth = Yii::$app->authManager;
        $auth->revokeAll(1);
        $auth->revokeAll(2);
        $auth->assign($auth->getRole('admin'), 2);
        $auth->assign($auth->getRole('user'), 1);
    }


    public function actionTest()
    {
        Yii::$app->set('request', new \yii\web\Request());
        $auth = Yii::$app->authManager;

        $user = new User(['id' => 1, 'username' => 'admin']);
        $admin = new User(['id' => 2, 'username' => 'demo']);

        $auth->revokeAll($user->id);
        $auth->revokeAll($admin->id);

        $auth->assign($auth->getRole('user'), $user->id);
        $auth->assign($auth->getRole('admin'), $admin->id);

        $post = new Post([
            'title' => 'Пример',
            'author_id' => $user->id
        ]);

        // Проверка доступа для user ######################################
        $this->stdout("Проверка доступа для {$user->username}\n\n", Console::FG_BLUE);
        Yii::$app->user->login($user);
        $this->show('Создание нового сообщения', Yii::$app->user->can('createPost'));
        $this->show('Просмотр данного сообщения', Yii::$app->user->can('viewPost', ['post' => $post]));
        $this->show('Это его собственное сообщение?', Yii::$app->user->can('viewOwnPost', ['post' => $post]));

        // Проверка доступа для admin ######################################
        $this->stdout("Проверка доступа для {$admin->username}\n\n", Console::FG_BLUE);
        Yii::$app->user->login($admin);
        $this->show('Создание нового сообщения', Yii::$app->user->can('createPost'));
        $this->show('Просмотр данного сообщения', Yii::$app->user->can('viewPost', ['post' => $post]));
        $this->show('Это его собственное сообщение?', Yii::$app->user->can('viewOwnPost', ['post' => $post]));

        echo PHP_EOL;

        /*        echo 'New roles for user: ' . PHP_EOL;
                print_r(implode(', ', ArrayHelper::map($auth->getRolesByUser($user->id),'name','name')));
                echo PHP_EOL;
                print_r(ArrayHelper::map($auth->getRolesByUser($user->id),'name','name'));
                echo PHP_EOL;

                Yii::$app->user->login($user);
                var_dump(Yii::$app->user->can('admin'));
                Yii::$app->user->login($admin);
                var_dump(Yii::$app->user->can('admin'));
        */
    }

    public function show($message, $value)
    {
        $result = $value ? 'Да' : 'Нет';
        $this->stdout("$message: $result\n\n", Console::FG_RED);
    }
}