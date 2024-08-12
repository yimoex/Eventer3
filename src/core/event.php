<?php
namespace Eventer\Core;

class Event {

    public $id;
    public $callback;
    public $timer = NULL;
    public $lasttime = 0;
    public $attr = [];

    public function __construct($id, $callback, $timer = 1){
        $this -> id = $id;
        $this -> callback = $callback;
        $this -> timer = $timer;
    }

    public function init($func){
        return $func($this);
    }

    public static function make($id, $callback, $timer = 1){
        return new Event($id, $callback, $timer);
    }

}
