<?php
namespace Eventer\Libs\Base;

class Listener {

	private static $listens = [];

	public static function listen($id, $event_id, $callback, $counts = -1) : bool {
		$event_id = self::getEventId($event_id);
		if(!$id) return false;
		$listen = new \stdclass;
		$listen -> event_id = $event_id;
		$listen -> id = $id;
		$listen -> callback = $callback;
		$listen -> counts = $counts;
		array_push(self::$listens, $listen);
		return true;
	}

	public static function unlisten($id) : bool {
		if(is_array($id)) return self::unlistenByArray($id);
		foreach(self::$listens as $key => $listen){
			if($listen -> id === $id){
				unset(self::$listens[$key]);
				return true;
			}
		}
		return false;
	}

	public static function unlistenAll($id) : bool {
		if(is_array($id)) return self::unlistenByArray($id);
		foreach(self::$listens as $key => $listen){
			if($listen -> id === $id) unset(self::$listens[$key]);
		}
		return true;
	}

	public static function setCounts($id, $counts) : bool {
		if(!isset(self::$listens[$id])) return false;
		(self::$listens[$id]) -> counts = $counts;
		return true;
	}

	public static function unlistenByArray(array $id_arr) : bool{
		foreach(self::$listens as $key => $listen){
			if(in_array($listen -> id, $id_arr)){
				unset(self::$listens[$key]);
			}
		}
		return false;
	}

	public static function getEventId($id){
		$arr = explode('.', $id);
		if(!is_array($arr)) return false;
		return $arr;
	}

	public static function emit($id, $data = NULL){
		$id = self::getEventId($id);
		foreach(self::$listens as $listen){
			if(!self::check($listen -> event_id, $id)) continue;
			($listen -> callback)($data);
			if($listen -> counts === -1) continue;
			if($listen -> counts === 0){
				self::unlisten($listen -> id);
				continue;
			}
			$listen -> counts--;
		}
	}

	public static function check(array $event_id, array $emit_id) : bool {
		foreach($emit_id as $key => $emitid){
			if($emitid === '*') continue;
			if(!isset($event_id[$key])) continue;
			$ev = $event_id[$key];
			if($ev === $emitid) continue;
			return false;
		}
		return true;
	}

}