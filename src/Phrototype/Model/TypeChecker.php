<?php

namespace Phrototype\Model;

class TypeChecker {
	private static $callbacks = [];
	public static function check($type, $value) {
		$callbacks = self::$callbacks;
		if(empty($callbacks)) {
			$callbacks = self::setCallbacks();
		}
		if(array_key_exists($type, $callbacks)) {
			return $callbacks[$type]($value);
		}
		return gettype($value) === $type;
	}

	public static function setCallbacks() {
		self::$callbacks = [
			'integer' => function($v) {
				return is_numeric($v);
			},
			'date' => function($v) {
				// This is not a good regex
				return preg_match('/^\d{4}-\d{2}-\d{2}$/', $v) === 1;
			},
		];

		return self::$callbacks;
	}
}