<?php
namespace Eventer\Package\YimoEx\Protocol;

use Eventer\Libs\Network\TcpConnection;
use Eventer\Libs\Base\Buffer;
use Eventer\Package\YimoEx\Libs\HttpRequest;
use Eventer\Package\YimoEx\Libs\HttpResponse;

class HttpConnection extends TcpConnection {

    public $request;

	public $options = [
		'ssl'  => [
			'verify_peer'      => false,
			'verify_peer_name' => false,
		]
	];

    public function __construct($addr){
        $param = $this -> parser_addr($addr);
        $this -> port = $port = $param['port'];
        $type = $param['ssl'] === true ? 'ssl' : 'tcp';
        $this -> addr = $type . '://' . $param['ip'] . ':' . $port;
        $this -> request = $http = new HttpRequest($param['addr'], $param['path'], $port);
        $this -> buffer = new Buffer('');
        $this -> onConnect = function($tcp) use ($http){
            $tcp -> send($http -> make());
        };
        $this -> create_time = microtime(true);
    }

    public function request(){
        return $this -> request;
    }

	public function response(){
		return new HttpResponse($this -> buffer -> get());
	}

    public function isDataEnd(){
        return $this -> buffer -> call(function($data){
            $k = substr($data, -5);
            return $k == "0\r\n\r\n" || $k == 'html>';
        });
    }

	private function parser_addr($addr){
        $arr = explode('://', $addr, 2);
        $type = $arr[0];
        $arr = explode('/', $arr[1], 2);
        $host = $arr[0];
        $path = '/' . ($arr[1] ?? '');
        if($type === 'http'){
            $port = 80;
            $ssl = false;
        }else if($type === 'https'){
            $port = 443;
            $ssl = true;
        }
        $arr = explode(':', $host, 2);
        $addr = $arr[0];
        if(isset($arr[1]) && (int)$arr[1] !== 0){
            $port = (int)$arr[1];
        }
        return [
            'host' => $host,
            'ip' => gethostbyname($addr),
            'addr' => $addr,  
            'port' => $port,
            'path' => $path,
            'ssl' => $ssl
        ];
	}

}