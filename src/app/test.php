<?php
namespace Eventer\App;

use Eventer\Core\Event;
use Eventer\Core\Eventer;

class Test {

    public function run(Eventer $eventer){
        $ev = Event::make(function(Event $event, Eventer $eventer){
            $event -> attr['execCount']++;
            printf("execCount: %d\n", $event -> attr['execCount']);
            if($event -> getAttr('execCount') == 3){
                $eventer -> unregister($event -> id); //卸载事件
                printf("unregister(%s)\n", $event -> id);
            }
        }, 2);
		$ev -> init(function($event){
            $event -> attr['execCount'] = 0;
        });
        $eventer -> register($ev); //2秒执行一次
    }

}