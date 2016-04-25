<?php
namespace frontend\controllers;

use common\models\Post;
use yii;
use yii\web\ForbiddenHttpException;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class RestpostController extends ActiveController
{
    public $modelClass = 'common\models\Post';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'except' => ['index', 'view'], // 'view' убрал, поскольку это мешало RBAC!!!!
        ];
        return $behaviors;
    }

    public function actions()
    {
        return array_merge(
            parent::actions(),
            [
                'index' => [
                    'class' => 'yii\rest\IndexAction',
                    'modelClass' => $this->modelClass,
                    'checkAccess' => [$this, 'checkAccess'],
                    'prepareDataProvider' => function ($action) {
                        /* @var $model Post */
                        $model = new $this->modelClass;
                        $query = $model::find();
                        $dataProvider = new ActiveDataProvider(['query' => $query]);

                        $model->setAttribute('title', @$_GET['title']);
                        $query->andFilterWhere(['like', 'title', $model->title]);

                        return $dataProvider;
                    }
                ]
            ]
        );
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if ($action == 'update') {
            if (!Yii::$app->user->can('updatePost', ['post' => $model])) {
                throw new ForbiddenHttpException('Редактирование запрещено!');
            }
        }
//        if ($action == 'delete') {
//            if (!Yii::$app->user->can('deletePost', ['post' => $model])) {
//                throw new ForbiddenHttpException('Удаление запрещено');
//            }
//        }

//        if ($action == 'view') {
//            if (!Yii::$app->user->can('viewPost', ['post' => $model])) {
//                throw new ForbiddenHttpException('Доступ запрещен!');
//            }
//        }
    }

    public function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH', 'POST'],
            'delete' => ['DELETE', 'POST'],
        ];
    }
}
