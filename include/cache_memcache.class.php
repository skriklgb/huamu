<?php
/*
	[Destoon B2B System] Copyright (c) 2008-2016 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
class dcache {
	var $pre;
	var $obj;
	var $con;
	var $time;

    function __construct() {
		$this->obj = new Memcache;
		include DT_ROOT.'/file/config/memcache.inc.php';
		$num = count($MemServer);
		$key = $num == 1 ? 0 : abs(crc32($GLOBALS['DT_IP']))%$num;
		$this->con = $this->obj->connect($MemServer[$key]['host'], $MemServer[$key]['port'], 2);
		if(!$this->con) $this->time = $GLOBALS['DT_TIME'];
    }

    function dcache() {
		$this->__construct();
    }

	function get($key) {
        if($this->con) return $this->obj->get($this->pre.$key);
		is_md5($key) or $key = md5($this->pre.$key);
		$php = DT_CACHE.'/php/'.substr($key, 0, 2).'/'.$key.'.php';
		if(is_file($php)) {
			$str = file_get($php);
			$ttl = substr($str, 13, 10);
			if($ttl < $this->time) return '';
			return substr($str, 23, 1) == '@' ? substr($str, 24) : unserialize(substr($str, 23));
		} else {
			return '';
		}
    }

    function set($key, $val, $ttl = 600) {
        if($this->con) return $this->obj->set($this->pre.$key, $val, 0, $ttl);
		is_md5($key) or $key = md5($this->pre.$key);
		$ttl = $this->time + $ttl;
		$val = '<?php exit;?>'.$ttl.(is_array($val) ? serialize($val) : '@'.$val);
		return file_put(DT_CACHE.'/php/'.substr($key, 0, 2).'/'.$key.'.php', $val);
    }

    function rm($key) {
        if($this->con) return $this->obj->delete($this->pre.$key);
		is_md5($key) or $key = md5($this->pre.$key);
		return file_del(DT_CACHE.'/php/'.substr($key, 0, 2).'/'.$key.'.php');
    }

    function clear() {
        if($this->con) return $this->obj->flush();
		@rename(DT_CACHE.'/php/', DT_CACHE.'/'.timetodate($this->time, 'YmdHis').'.tmp/');
    }

	function expire() {
		return true;
	}
}
?>