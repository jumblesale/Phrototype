<?php

namespace Phrototype;

class Logue {
	private static $level = 5;

	const OFF	= 0;
	const FATAL	= 1;
	const WARN	= 2;
	const INFO	= 3;
	const DEBUG	= 4;
	const ROYBATTY	= 5;

	private function __construct() {
		// go away
	}

	public static function level($v = null) {
		return $v ?
			  self::$level = $v
			: self::$level;
	}

	public static function log($message, $level = 5) {
		if(self::$level === null) {self::level(5);}
		if(self::$level >= $level) {
			// TODO: write this to a file or something!
			error_log($message);
			return true;
		}
		return false;
	}

	public static function dump($label, $data, $level = 5) {
		return self::log(
			implode(': ', [$label, print_r($data, true)]),
			$level
		);
	}
}