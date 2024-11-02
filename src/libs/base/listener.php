<?php
namespace Eventer\Libs\Base;

class Listener {

	private static $listens = [];

	/**
	 * 监听事件
	 * @param string $id 监听器ID
	 * @param string $event_id 监听事件
	 * @param callable $callback 回调器
	 * @param int $counts 次数(-1为无限,0为无法执行)
	 * @return bool
	 */
	public static function listen(string $id, string $event_id, callable $callback, int $counts = -1) : bool {
		$event_id = self::getEventId($event_id);
		if(isset(self::$listens[$id]) || $event_id === false) return false;
		$listen = new \stdclass;
		$listen -> event_id = $event_id;
		$listen -> id = $id;
		$listen -> data = NULL;
		$listen -> callback = $callback;
		$listen -> counts = $counts;
		self::$listens[$id] = $listen;
		return true;
	}

	public static function unlisten($id) : bool {
		if(is_array($id)) return self::unlistenByArray($id);
		if(!isset(self::$listens[$id])) return false;
		unset(self::$listens[$id]);
		return false;
	}

	public static function setCounts(string $id, int $counts) : bool {
		if(!isset(self::$listens[$id])) return false;
		(self::$listens[$id]) -> counts = $counts;
		return true;
	}

	public static function bindData(string $id, $data) : bool {
		if(!isset(self::$listens[$id])) return false;
		(self::$listens[$id]) -> data = $data;
		return true;
	}

	public static function getListen(string $id) {
		if(!isset(self::$listens[$id])) return false;
		return self::$listens[$id];
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
			if($listen -> counts === 0){
				self::unlisten($listen -> id);
				continue;
			}
			$listen -> counts--;
			if($listen -> counts === -1) continue;
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