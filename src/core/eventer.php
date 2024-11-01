<?php
namespace Eventer\Core;

use Eventer\Libs\Network\ConnectionPool;

class Eventer {

    protected $_events;
    protected $_signal = [];
    public $attr = NULL;

    public function __construct(){
        $this -> init();
    }

    protected function init(){
        define('ROOT', dirname(__DIR__));

        include_once ROOT . '/core/function.php';

        $this -> attr = $attr = new \stdClass;
        $attr -> tps = 0;
        $attr -> counts = 0;
        $attr -> task_counts = 0;
        $this -> findApp();
    }

    protected function findApp(){
        $apps = require(ROOT . '/app/app.php');
        if($apps == NULL) return false;
		foreach($apps as $app){
			(new $app) -> run($this);
		}
    }

    public function register(Event $ev){
        $this -> _events[] = $ev;
        $this -> attr -> counts++;
        return true;
    }

    /**
     * 核心运行库
     * @return void
     */
    public function run(){
        if(empty($this -> _events)) return;
        while(1){
            ConnectionPool::run();
            foreach($this -> _events as $event){
                $i = (int)microtime(true) - $event -> lasttime;
                if($event -> timer > $i) continue;
                $caller = $event -> callback;
                $caller($event);
                $event -> lasttime = (int)microtime(true);
            }
            usleep(100);
        }
    }

}
