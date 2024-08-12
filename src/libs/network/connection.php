<?php
namespace Eventer\Libs\Network;

use Eventer\Libs\Base\Buffer;
use Eventer\Core\networkStatus;

class Connection {

    public $id = 0;
    public $addr;
    public $port;
    public $sock;
    public $buffer = '';
    public $bufferSize = 8192;
    public $timeout = 1.25;
    public $options = [];
    public $connectCount = 0;
    public $maxReconnectCount = 3;

    public $times = [
        'start_time' => 0,
        'connected_time' => 0,
        'update_time' => 0,
        'create_time' => 0
    ];
    public $status = networkStatus::STATUS_CONNECTING;
    public $type;
    public $attr = [];

    public $onConnect;
    public $onMessage;
    public $onTimeout;
    public $onClose;
    public $onError;


    public function __construct($addr, $port){
        $this -> buildAddr($addr, $port);
        $this -> buffer = new Buffer();
        $this -> times['create_time'] = (int)microtime(true);
    }

    public function buildAddr($addr, $port){
        $this -> addr = $addr;
        $this -> port = $port;
    }

    public function connect() : bool {
        return $this -> _connect();
    }

    protected function _connect($type = STREAM_CLIENT_CONNECT) : bool {
        $context = stream_context_create($this -> options);
        $this -> type = (int)$type;
        $sock = stream_socket_client($this -> addr, $errno, $errmsg, $this -> timeout, (int)$type, $context);
        if(!$sock){
            $this -> event('Error', $errno, $errmsg);
            $this -> sock = NULL;
            return false;
        }
        stream_set_timeout($sock, $this -> timeout);
        stream_set_blocking($sock, 0);
        $this -> sock = $sock;
        $this -> times['start_time'] = microtime(true);
        $this -> times['update_time'] = microtime(true);
        return true;
    }

    public function reconnect() : bool {
        if($this -> status !== networkStatus::STATUS_CLOSE) return false;
        if($this -> connectCount >= $this -> maxReconnectCount){
            return false;
        }
        $this -> status = networkStatus::STATUS_CONNECTING;
        $this -> connectCount++;
        return $this -> connect();
    }

    public function read(){
        if($this -> status !== networkStatus::STATUS_CONNECTED) return false;
        if(feof($this -> sock)){
            return $this -> close('Connection::read()');
        }
        $rec = fread($this -> sock, $this -> bufferSize);
        if($rec === 0 || $rec === '') return true;
        $this -> buffer -> add($rec);
        return true;
    }
    
    public function send($data){
        if($this -> status !== networkStatus::STATUS_CONNECTED) return false;
        if(feof($this -> sock)){
            return $this -> close('Connection::send()');
        }
        $rec = fwrite($this -> sock, $data);
        return $rec;
    }

    public function isTimeout(){
        $timer = (microtime(true) - $this -> times['start_time']);
        return $timer > $this -> timeout;
    }

    public function close($signal = NULL) : bool {
        $this -> status = networkStatus::STATUS_CLOSE;
        if(is_resource($this -> sock)){
            fclose($this -> sock);
            $this -> sock = NULL;
        }
        if($this -> type === STREAM_CLIENT_ASYNC_CONNECT){
            ConnectionPool::remove($this -> id);
            $this -> id = 0;
        }
        if(!$this -> buffer -> isEmpty()){
            $this -> event('Message', $this, $this -> response());
        }
        $this -> event('Close', $this, $signal);
        if($this -> status === networkStatus::STATUS_CONNECTING) return true; //判断是否重连
        $this -> onConnect = 
        $this -> onClose = 
        $this -> onMessage = 
        $this -> onError = 
        $this -> onTimeout = NULL;
        return false;
    }

    public function response(){
        return $this -> buffer -> get();
    }

    public function event(string $id, ...$param){
        $id = 'on' . $id;
        $obj = $this -> $id;
        if(!is_object($obj)) return;
        return $obj(...$param);
    }

}