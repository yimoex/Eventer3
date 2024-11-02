<?php
namespace Eventer\Core;

class Event {

    public $id;
    public $callback;
    public $timer = NULL;
    public $lasttime = 0;
    public $attr = [];

    public function __construct($callback, $timer = 1){
        $this -> callback = $callback;
        $this -> timer = $timer;
    }

    public function init($func){
        return $func($this);
    }

    public function setAttr($key, $value){
        $this -> attr[$key] = $value;
        return true;
    }

    public function getAttr($key, $default = NULL){
        return $this -> attr[$key] ?? $default;
    }

    public static function make($callback, $timer = 1){
        return new Event($callback, $timer);
    }

}
