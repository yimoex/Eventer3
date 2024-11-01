<?php
namespace Eventer\Package\YimoEx\Ai;

use Eventer\Package\YimoEx\Libs\HttpResponse;
use Eventer\Package\YimoEx\Protocol\AsyncHttpConnection;

class Controller {
    
    const API = 'https://api/v1';

    const KEY = 'sk-666';

    public $header = [
        'Authorization: Bearer ',
        'Content-Type: application/json'
    ];

    public $model = 'none';

    public static function create(){
        $c = new static();
        $c -> header[0] .= static::KEY;
        return $c;
    }

    public function send(string $system_init, string $message, object $func){
        $param = json_encode([
            'model' => $this -> model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $system_init
                ],[
                    'role' => 'user',
                    'content' => $message
                ]
            ]
        ]);
        $api = static::API . '/chat/completions';
        $http = new AsyncHttpConnection($api);
        $req = $http -> request();
        $req -> setHeader('Authorization', 'Bearer ' . static::KEY);
        $req -> setHeader('Content-Type', 'application/json');
        $req -> type = 'POST';
        $req -> data = $param;
        $http -> onMessage = function($http, HttpResponse $data) use ($func) {
            $data = json_decode($data -> get(), true)['choices'][0]['message']['content'];
            $func($data);
        };
        $http -> connect();
    }
}
