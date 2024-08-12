<?php
namespace Eventer\Libs\Base;

class Io {

    protected $file;
    protected $stream;
    protected $status = true;

    public function __construct(string $file, string $mods){
        $this -> file = $file = ROOT . '/public/' . $file;
        $dir = dirname($file);
        if(!is_dir($dir)){
            mkdir($dir);
        }
        $this -> stream = $stream = fopen($file, $mods);
        $this -> status = $stream == false ? false : true;
    }

    public function __destruct(){
        $this -> close();
    }

    public function write(string $data){
        if($this -> status === false) return false;
        $rec = fwrite($this -> stream, $data);
        if($rec === false) return false;
        return $this;
    }

    public function read(int $size) : string {
        if($this -> status === false) return false;
        return fread($this -> stream, $size);
    }

    public function size() : int {
        return filesize($this -> file);
    }

    public function call(\Closure $callback){
        if($this -> status === false) return false;
        return $callback($this -> stream);
    }

    public function close(){
        $this -> status = false;
        if(is_resource($this -> stream)) fclose($this -> stream);
    }



}
