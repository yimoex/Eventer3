<?php
namespace Eventer\Package\YimoEx\Protocol;

use Eventer\Libs\Network\ConnectionPool;

class AsyncHttpConnection extends HttpConnection {

    public $id = NULL;

    public function connect() : bool {
        if(!$this -> _connect(STREAM_CLIENT_ASYNC_CONNECT)) return false;
        $this -> id = ConnectionPool::push($this);
        return true;
    }
}
