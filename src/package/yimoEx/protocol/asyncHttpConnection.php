<?php
namespace Eventer\Package\YimoEx\Protocol;

use Eventer\Libs\Network\ConnectionPool;

class AsyncHttpConnection extends HttpConnection {

    public $id = NULL;

    public function connect() : bool {
        $type = STREAM_CLIENT_ASYNC_CONNECT;
        if($this -> protocol === 'ssl') $type = STREAM_CLIENT_CONNECT;
        if(!$this -> _connect($type)) return false;
        $this -> id = ConnectionPool::push($this);
        return true;
    }
}
