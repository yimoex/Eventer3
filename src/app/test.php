<?php
namespace Eventer\App;

use Eventer\Core\Event;
use Eventer\Core\Eventer;

class Test {

    public function run(Eventer $eventer){
        $ev = Event::make('test', function(Event $event){
            $event -> attr['execCount']++;
            printf("execCount: %d\r", $event -> attr['execCount']);
        }, 2);
		$ev -> init(function($event){
            $event -> attr['execCount'] = 0;
        });
        $eventer -> register($ev); //2秒执行一次
    }

}