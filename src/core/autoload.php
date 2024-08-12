<?php
namespace Eventer\Core;


class Loader {

	public static function run(){
		spl_autoload_register('\\Eventer\\Core\\Loader::autoload');
	}
	
	public static function unrun(){
		spl_autoload_unregister('\\Eventer\\Core\\Loader::autoload');
	}

	public static function autoload($class){
		$class = str_replace('\\', '/', $class);
		$arr = explode('/',$class);
		if($arr[0] !== 'Eventer') return false;
		unset($arr[0]);
		$arr = array_values($arr);
		$path = '';
		$count = count($arr);
		for($i = 0;$i < $count; $i++){
			$tmp = $arr[$i];
			$tmp = $i === ($count-1) ? lcfirst($tmp) : $tmp .= '/';
			$path .= $tmp;
		}
		$path .= '.php';
		if(is_file($path)){
			include $path;
		}else{
			printf("Error: 无法加载 %s 的文件\r\n", $path);
			die();
		}
	}

}