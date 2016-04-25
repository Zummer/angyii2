<?php
/**
 * Created by PhpStorm.
 * User: afanasev
 * Date: 23.04.16
 * Time: 21:41
 */

namespace console\controllers;

use Yii;
use yii\console\Controller;
use \common\models\User;

class EventController extends Controller
{

    public function actionLoad()
    {
        $loader = new Loader();

//        $loader->off(Loader::EVENT_ERROR, [$loader, 'handleEventError']);

        $loader->load('http://yandex.ru');
        $loader->load('sdfsdf');
    }

    public function actionUser()
    {
        Yii::$app->set('request', new \yii\web\Request());
        $user = new User(['id' => 1, 'username' => 'User123']);
        Yii::$app->user->login($user);
    }
}

class Component
{
    private $_events = [];

    public function on($name, $handler)
    {
        $this->_events[$name][] = $handler;
    }

    public function off($name, $handler)
    {
        if (!empty($this->_events[$name])) {
            foreach ($this->_events[$name] as $i => $item) {
                if ($item === $handler) {
                    unset($this->_events[$name][$i]);
                }
            }
        }
    }

    public function trigger($name, $event = null)
    {
        if (!empty($this->_events[$name])) {
            if ($event === null) {
                $event = new Event($name);
            }
            if ($event->sender === null) {
                $event->sender = $this;
            }
            foreach ($this->_events[$name] as $handler) {
                call_user_func($handler, $event);
            }
        }
    }
}

class Event
{
    public $name;
    public $sender;
    public $data;

    public function __construct($name)
    {
        $this->name = $name;
    }
}

class Loader extends Component
{
    /** @property $responce */

    const EVENT_SUCCESS = 'success';
    const EVENT_ERROR = 'error';

    public $responce;
//    public $errorMessage;

    public function __construct()
    {
        $this->on(Loader::EVENT_SUCCESS, [$this, 'handleEventSuccess']);
        $this->on(Loader::EVENT_ERROR, [$this, 'handleEventError']);
    }

    public function handleEventSuccess(LoaderEvent $event) {
        /** @var Loader $sender*/ // заработало и помогло!
        $sender = $event->sender;
        echo $sender->responce . ', '. $event->name .PHP_EOL;
    }
    public function handleEventError (LoaderEvent $event) {
        echo $event->errorMessage . ', '. $event->name  . PHP_EOL;
    }

    public function load($url){
        if ($url == 'http://yandex.ru'){
            $this->responce = "Правильно!";
            $event = new LoaderEvent(self::EVENT_SUCCESS);
            $this->trigger($event->name, $event);
        } else {
            $event = new LoaderEvent(self::EVENT_ERROR);
//            $this->errorMessage = "Не правильно!";
            $event->errorMessage = "Не правильно!";
            $this->trigger($event->name, $event);
        }
    }
}

class LoaderEvent extends Event
{
    public  $errorMessage;
}