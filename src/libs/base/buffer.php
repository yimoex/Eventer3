<?php
namespace Eventer\Libs\Base;

class Buffer {

    protected $data;
    protected $dataSize;

    public function __construct($data = ''){
        $this -> data = $data;
        $this -> dataSize = strlen($data);
    }

    public function length(){
        return $this -> dataSize;
    }

    public function update($data){
        $this -> data = $data;
        $this -> dataSize = strlen($data);
        return $this;
    }

    public function add($data){
        $this -> data .= $data;
        $this -> dataSize += strlen($data);
        return $this;
    }

    public function get(){
        return $this -> data;
    }

    public function clear(){
        $this -> data = '';
        $this -> dataSize = 0;
        return $this;
    }

    public function isEmpty(){
        return $this -> dataSize === 0;
    }

    public function call(\Closure $obj){
        return $obj($this -> data, $this -> dataSize);
    }

}