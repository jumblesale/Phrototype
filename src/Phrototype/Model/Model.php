<?php

namespace Phrototype\Model;

use Phrototype\Prototype;
use Phrototype\Model\TypeChecker;

class Model {
	public static function create(
		array $fields = array(), $prototype = null
	) {
		$fields = self::processFields($fields);
		$obj = Prototype::create($fields, $prototype);
		// Assign the prototype the load method
		$obj->load = function(array $data = array(), $prototype = null) {
			return self::load($data, $prototype);
		};
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
					$value = TypeChecker::check($field['type'], $value) ?
						  $value
						: null;
				}
			}
			$args[$name] = $value;
		}
		return $args;
	}

	public static function load(array $data = array(), $prototype = null) {
		$objects = [];
		foreach($data as $datum) {
			// echo "\n"; print_r($datum); echo "\n"; print_r($prototype); echo "\n";
			$objects[] = self::create($datum, $prototype);
		}
		return $objects;
	}
}