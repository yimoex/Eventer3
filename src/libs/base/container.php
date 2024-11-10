<?php
namespace Eventer\Libs\Base;

class Container {

    protected static $instance = NULL;
    protected static $packs = [];

    public static function getInstance(){
        if(static::$instance == NULL){
            static::$instance = new Container();
        }
        return static::$instance;
    }

    public static function export($id, $class){
        if(isset(self::$packs[$id])) return false;
        self::$packs[$id] = $class;
        return true;
    }

    public static function import($id, $defaultValue = NULL){
        return self::$packs[$id] ?? $defaultValue;
    }

}
