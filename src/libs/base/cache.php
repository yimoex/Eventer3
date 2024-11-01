<?php
namespace Eventer\Libs\Base;

class Cache {

    protected static $_nodes = [];

    public static function create(string $id){
        $node = new \stdClass;
        $node -> id = $id;
        $node -> locked = false;
        $node -> update_time = (int)microtime(true);
        $node -> data = new Buffer();
        self::$_nodes[$id] = $node;
        return $node;
    }

    public static function set(string $id, string $data) : bool {
        if(!isset(self::$_nodes[$id])) return false;
        $node = self::$_nodes[$id];
        $node -> data -> update($data);
        return true;
    }

    public static function call(string $id, \Closure $callback){
        if(!isset(self::$_nodes[$id])) return false;
        $node = self::$_nodes[$id];
        if($node -> locked) return false;
        return $node -> data -> call($callback);
    }

    public static function get(string $id){
        if(!isset(self::$_nodes[$id])) return false;
        $node = self::$_nodes[$id];
        return $node -> data;
    }

    public static function lock(string $id) : bool {
        if(!isset(self::$_nodes[$id])) return false;
        $node = self::$_nodes[$id];
        $node -> locked = true;
        return true;
    }

    public static function unlock(string $id) : bool {
        if(!isset(self::$_nodes[$id])) return false;
        $node = self::$_nodes[$id];
        $node -> locked = false;
        return true;
    }

    public function isTimeout(string $id) : bool {
        if(!isset(self::$_nodes[$id])) return false;
        $node = self::$_nodes[$id];
        if($node -> timeout === 0) return false;
        return (int)(microtime(true) - $node -> update_time) > $node -> timeout;
    }

    public function update(string $id) : bool {
        if(!isset(self::$_nodes[$id])) return false;
        $node = self::$_nodes[$id];
        $node -> update_time = (int)microtime(true);
        return true;
    }

}