<?php
/**
 * Created by PhpStorm.
 * User: afanasev
 * Date: 24.04.16
 * Time: 10:50
 */

namespace common\components;

use common\models\Post;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\log\Logger;

class ShopNotificator implements BootstrapInterface
{
    public function bootstrap($app)
    {
        // TODO: Implement bootstrap() method.
        Event::on(\yii\web\User::className(), \yii\web\User::EVENT_AFTER_LOGIN, [$this, 'userAfterLogin']);
        Event::on(Post::className(), ActiveRecord::EVENT_AFTER_INSERT, [$this, 'postCreated']);
        Event::on(Post::className(), Post::EVENT_STATUS_CHANGE, [$this, 'postStatusChanged']);
    }

    public function postStatusChanged(Event $event)
    {
        // Отправляем уведомление о заказе администратору, продавцу, клиенту
        /** @var Post $post **/
        $post = $event->sender;
        \Yii::info('Post Id = ' . $post->id . ', СТАТУС изменен.', 'shopNotifier');
    }
    public function postCreated(Event $event)
    {
        // Отправляем уведомление о заказе администратору, продавцу, клиенту
        /** @var Post $post **/
        $post = $event->sender;
//        echo 'Статья ' . $post. ' создана!' . PHP_EOL;
        \Yii::info('Post Id = ' . $post->id . ', создан.', 'shopNotifier');
    }

    public function userAfterLogin(\yii\web\UserEvent $event)
    {
        // Два обработчика
        $this->userAfterLogin1($event);
        $this->userAfterLogin2($event);
    }

    public function userAfterLogin1(\yii\web\UserEvent $event)
    {
        // Сохраняем информацию о входе
        /** @var \common\models\User $user **/
        $user = $event->identity;
//        echo
//        \Yii::info(get_class($user) . ' подключился777.');
        $logger = \Yii::getLogger();
        $logger->log('Пользователь ' . $user->username . ' залогинился!', Logger::LEVEL_INFO, 'shopNotifier');
    }
    public function userAfterLogin2(\yii\web\UserEvent $event)
    {
        // Сохраняем информацию о входе
        /** @var \common\models\User $user **/
        $user = $event->identity;
//        echo 'Пользователь ' . $user->username . ' подключился 2!' . PHP_EOL;
    }

    public function opinionCreated()
    {
        // Отправляем уведомление о новом отзыве продавцу
    }
}