<?php

namespace Phrototype\Model;

use Phrototype\Prototype;
use Phrototype\Model\TypeChecker;

class Factory {
	public static function create(
		array $fields = array(), $prototype = null
	) {
		$fields = self::processFields($fields);
		$obj = Prototype::create(
			$fields, $prototype, 'Phrototype\Model\Model');
		return $obj;
	}

	public static function processFields($fields) {
		$args = [];
		foreach ($fields as $name => $field) {
			$value = null;
			if(
				!is_array($field)
				|| (
					   gettype($field) == 'object'
					&& is_a($field, 'Phrototype\Model\Model')
				)
			) {
				$args[$name] = $field;
				continue;
			}
			if(array_key_exists('value', $field)) {
				$value = $field['value'];
			}
			$args[$name] = $value;
		}
		return $args;
	}

	public static function load(array $data = array(), $prototype = null) {
		$objects = [];
		foreach($data as $datum) {
			$objects[] = self::create($datum, $prototype);
		}
		return $objects;
	}
}