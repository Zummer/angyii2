<?php
/**
 * Created by PhpStorm.
 * User: afanasev
 * Date: 20.04.16
 * Time: 20:09
 */

namespace common\rbac;

use yii\rbac\Rule;

class AuthorRule extends Rule
{
    public $name = 'isAuthor';

    public function execute($userId, $item, $params)
    {
        return isset($params['post']) ? $params['post']->author_id == $userId : false;
    }
}