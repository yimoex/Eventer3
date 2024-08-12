<?php
namespace Eventer\Libs\Network;

use Eventer\Core\networkStatus;

class TcpConnection extends Connection {

    public function buildAddr($addr, $port){
        $this -> addr = 'tcp://' . $addr . ':' . $port;
        $this -> port = $port;
    }

    public function connect() : bool {
        if(!$this -> _connect()) return false;
        if(!is_object($this -> onMessage)) return false;
        $this -> status = networkStatus::STATUS_CONNECTED;
        $this -> times['connected_time'] = microtime(true);
        $this -> event('Connect', $this);
        while(1){
            $rec = $this -> read();
            if($rec === false){
                $this -> event('Close', $this);
                $this -> close();
                return false;
            }
            if($this -> buffer -> length() == 0) continue;
            $this -> event('Message', $this, $this -> response());
            $this -> buffer -> clear();
            usleep(100);
        }
    }

}
