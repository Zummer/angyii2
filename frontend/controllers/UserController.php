<?php
/**
 * Created by PhpStorm.
 * User: afanasev
 * Date: 09.04.16
 * Time: 8:43
 */

namespace frontend\controllers;

use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';
}