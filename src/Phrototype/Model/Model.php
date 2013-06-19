<?php

namespace Phrototype\Model;

use Phrototype\Prototype;

class Model {
	public static function create(
		array $fields = array(), $prototype = null
	) {
		$fields = self::processFields($fields);
		$obj = Prototype::create($fields, $prototype);
		return $obj;
	}

	public static function processFields($fields) {
		$args = [];
		foreach ($fields as $name => $field) {
			$value = null;
			if(!is_array($field)) {
				$args[$name] = $field;
				continue;
			}
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

	public static function load(array $data = array(), $prototype = null) {
		$objects = [];
		foreach($data as $datum) {
			$objects[] = self::create($datum, $prototype);
		}
		return $objects;
	}
}