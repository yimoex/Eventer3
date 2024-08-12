<?php
namespace Eventer\Libs\Network;

use Eventer\Core\networkStatus;

class ConnectionPool {

    protected static $_connections = [];
    public static $connectionSize = 0;

    public static function push(Connection $connection){
        $id = self::findId();
        $connection -> id = $id;
        self::$_connections[$id] = $connection;
        self::$connectionSize++;
        return $id;
    }

    public static function run(){
        foreach(self::$_connections as $connection){
            $index = $connection -> id;

            
            if($connection -> status === networkStatus::STATUS_CONNECTING){
                if($connection -> isTimeout()){
                    $connection -> event('Timeout', $connection);
                    $connection -> close('ConnectionPool::timeout()');
                    continue;
                }

                if(stream_socket_get_name($connection -> sock, true)){
                    $connection -> status = networkStatus::STATUS_CONNECTED;
                    $connection -> times['connected_time'] = microtime(true);
                    $connection -> event('Connect', $connection);
                }

                continue;
            }

            $rec = $connection -> read();
            if($rec === false){
                $connection -> close('ConnectionPool::Read()');
                self::remove($index);
                continue;
            }
            if($connection -> buffer -> length() == 0) continue;
            $connection -> event('Message', $connection, $connection -> response());
            $connection -> buffer -> clear();
        }
    }

    public static function findId() : string {
        return uniqid();
    }

    public static function remove($id) : bool {
        if(!isset(self::$_connections[$id])) return false;
        self::$connectionSize--;
        unset(self::$_connections[$id]);
        return true;
    } 

}