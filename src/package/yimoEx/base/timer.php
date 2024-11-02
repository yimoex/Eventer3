<?php
namespace Eventer\Package\YimoEx\Base;

use Eventer\Core\Event;
use Eventer\Core\Eventer;
use Eventer\Libs\Base\Listener;

class Timer {

    public $eventer;

    public function __construct(Eventer $eventer){
        $this -> eventer = $eventer;
    }

    public function create(string $date, callable $caller, float $internal = 60.0) {
        $time = strtotime($date);
        $container = new \stdClass;
        $container -> time = $time;
        $container -> caller = $caller;
        Listener::listen('timer', 'core.timer', function($time){
            $container = Listener::getListen('timer') -> data;
            $caller = $container -> caller;
            $time = $container -> time;
            if(is_callable($caller)) $caller($time);
        }, 1);

        Listener::bindData('timer', $container);

        $this -> eventer -> register(Event::make(function(Event $event, Eventer $eventer){
            $container = Listener::getListen('timer') -> data;
            if(time() < $container -> time) return;
            Listener::emit('core.timer', time());
            $eventer -> unregister($event -> id);
            Listener::emit('core.timer', time());
        }, $internal));
    }

}
