<?php
namespace Package\YimoEx\Protocol;

use Eventer\Libs\Network\Connection;

class Proxy {

    public static function pipe(Connection $local, Connection $remote){
        $local -> onMessage = function($connection, $data) use ($remote) {
            $remote -> send($data);
        };
        $remote -> onMessage = function($connection, $data) use ($local) {
            $local -> send($data);
        };
        $local -> connect();
        $remote -> connect();
    }

}
