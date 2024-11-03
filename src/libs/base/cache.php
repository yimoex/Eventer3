<?php
namespace Eventer\Libs\Base;

class Cache {

    protected static $_nodes = [];

    /**
     * 创建缓存
     * @param string $id 缓存键
     * @param mixed $defaultData 初始化默认数据
     * @return bool
     */
    public static function create(string $id, $defaultData = NULL): bool {
        if(isset(self::$_nodes[$id])) return false;
        $node = new \stdClass;
        $node -> id = $id;
        $node -> locked = false;
        $node -> data = $defaultData;
        self::$_nodes[$id] = $node;
        return true;
    }

    /**
     * 写入数据(覆盖)
     * @param string $id 缓存键
     * @param mixed $data 数据
     * @return bool
     */
    public static function set(string $id, mixed $data){
        if(!isset(self::$_nodes[$id])) return false;
        if((self::$_nodes[$id]) -> locked) return false;
        (self::$_nodes[$id]) -> data = $data;
        return true;
    }

    /**
     * 获取数据
     * @param string $id 缓存键
     * @return mixed
     */
    public static function get(string $id){
        if(!isset(self::$_nodes[$id])) return false;
        return (self::$_nodes[$id]) -> data;
    }

    /**
     * 回调
     * @param string $id 缓存键
     * @param callable $caller 回调函数
     * @return bool
     */
    public static function caller(string $id, callable $caller){
        if(!isset(self::$_nodes[$id])) return false;
        (self::$_nodes[$id]) -> data = $caller((self::$_nodes[$id]) -> data);
        return true;
    }

    /**
     * 锁定/解锁缓存块
     * @param string $id 缓存键
     * @param bool $enable 是否锁定
     * @return bool
     */
    public static function lock(string $id, bool $enable = true){
        if(!isset(self::$_nodes[$id])) return false;
        (self::$_nodes[$id]) -> locked = $enable;
        return true;
    }

    /**
     * 缓存是否存在
     * @param string $id 缓存键
     * @return bool
     */
    public static function exist(string $id){
        return isset(self::$_nodes[$id]);
    }

}
