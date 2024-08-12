<?php
namespace Eventer\Package\YimoEx\Libs;

class HttpRequest {

    public $host = '';
    public $port = 0;
    public $path = '/';
    public $type = 'GET';
    public $protocol = 'HTTP/1.1';
	public $header = [
        'Connection' => 'close',
        'Accept' => 'text/html',
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36',
	];
    public $data = [];

    public function __construct($host, $path = '/', $port = 80){
        $this -> host = $host;
        $this -> path = $path;
        $this -> port = (int)$port;
    }

    public function set($key, $value){
        if(!isset($this -> $key)) return false;
        $this -> $key = $value;
        return true;
    }

    public function setHeader($key, $value){
        $this -> header[$key] = $value;
        return true;
    }

    public function setCookie($cookies){
        $this -> header['Cookie'] = $cookies;
    }

    public function make(){
        $head = '';
        $path = $this -> path;
        if($this -> type === 'GET' && $this -> data !== []){
            $path .= '?' . http_build_query($this -> data);
        }
        $this -> add($head, $this -> type . ' ' . $path . ' ' . $this -> protocol);
        foreach($this -> header as $key => $value){
            $this -> add($head, $key . ': ' . $value);
        }
        $this -> add($head, 'Host: ' . $this -> host . ':' . $this -> port);
        $this -> add($head, '');
        if($this -> type !== 'GET' && $this -> data != NULL){
            $this -> add($head, $this -> data);
            $this -> add($head, '');
        }
        return $head;
    }

    public function import($httpPacket){
        $this -> header = [];
        $arr = explode("\r\n\r\n", $httpPacket, 2);
        $this -> data = $arr[1] ?? '';
        $httpPacket = $arr[0];
        $arr = explode("\r\n", $httpPacket);
        $this -> import_head_parser(array_shift($arr));
        $this -> import_main_parser($arr);
        return true;
    }

    private function import_head_parser($head){
        $arr = explode(' ', $head, 3);
        $this -> type = $arr[0];
        $this -> path = $arr[1];
        $this -> protocol = $arr[2];
    }

    private function import_main_parser($body){
        $header = [];
        foreach($body as $id => $v){
            $arr = explode(':', $v, 2);
            if(count($arr) !== 2) continue;
            $key = $arr[0];
            $value = trim($arr[1]);
            $header[$key] = $value;
        }
        $host = $header['Host'];
        $arr = explode(':', $host, 2);
        if(count($arr) === 2){
            $this -> host = $arr[0];
            $this -> port = $arr[1];
        }else{
            $this -> host = $arr[0];
            $this -> port = 80;
        }
        unset($header['Host']);
        $this -> header = $header;
    }

    private function add(&$val, $msg){
        $val .= $msg . "\r\n";
    }

}