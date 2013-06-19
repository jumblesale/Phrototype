<?php

namespace Phrototype\Model;

use Phrototype\Prototype;

class Model {
	public static function create(array $prototype = array()) {
		$args = self::processFields($prototype);
		$obj = new Prototype($args);
		return $obj;
	}

	public static function processFields($fields) {
		$args = [];
		foreach ($fields as $name => $field) {
			$value = null;
			if(array_key_exists('value', $field)) {
				$value = $field['value'];
				if(array_key_exists('type', $field)) {
					$value = self::checkType($field['type'], $value);
				}
			}
			$args[$name] = $value;
		}
		return $args;
	}

	public static function checkType($type, $value) {
		if($type == 'integer') {
			return is_numeric($value) ? $value : null;
		}
		if($type == 'date') {return $value;}
		return gettype($value) === $type ?
			  $value
			: null;
	}

	public function load(array $data = array()) {}
}