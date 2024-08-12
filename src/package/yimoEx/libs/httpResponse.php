<?php
namespace Eventer\Package\YimoEx\Libs;

class HttpResponse {

    public $status_code = -1;
    public $status = '';
    public $protocol = '';
    public $attr = [];
    public $body = '';
    public $bodyLength = 0;
    public $rawLength = 0;

    public function __construct($http){
        $this -> import($http);
    }

    public function isError(){
        return $this -> status_code === -1;
    }

    public function import($http){
        $arr = explode("\r\n\r\n", $http, 2);
        $head = explode("\r\n", $arr[0]);
        $_head = explode(' ', array_shift($head), 3);
        $this -> protocol = $_head[0];
        $this -> status_code = $_head[1];
        $this -> status = $_head[2];
        $this -> attr = $this -> getValue($head);
        $this -> getBody($arr[1] ?? '');
    }

    public function get(){
        return $this -> body;
    }

    private function getBody($body){
        $length = (int)($this -> attr['Content-Length'] ?? 0);
        if($length === 0){
            $this -> getBodyNext($body);
        }else{
            $this -> body = substr($body, 0, $length);
            $this -> bodyLength = $length;
        }
    }

    private function getBodyNext($body){
        $arr = explode("\r\n", $body);
        $size = count($arr) - 3; //数据包个数
        $data = '';
        for($i = 1;$i < $size;$i+=2){
            $data .= $arr[$i];
        }
        $this -> body = $data;
        $this -> bodyLength = strlen($data);
    }

    private function getValue($array){
        $res = [];
        foreach($array as $v){
            $arr = explode(':', $v);
            $res[$arr[0]] = trim($arr[1]);
        }
        return $res;
    }

}