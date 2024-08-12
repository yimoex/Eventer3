<?php
namespace Eventer\Libs\Network;

class AsyncTcpConnection extends Connection {

    public function buildAddr($addr, $port){
        $this -> addr = 'tcp://' . $addr . ':' . $port;
        $this -> port = $port;
    }

    public function connect() : bool {
        if(!$this -> _connect(STREAM_CLIENT_ASYNC_CONNECT)) return false;
        ConnectionPool::push($this);
        return true;
    }

}
