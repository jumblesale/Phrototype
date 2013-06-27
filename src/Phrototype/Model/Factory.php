<?php

namespace Phrototype\Model;

use Phrototype\Prototype;
// use Phrototype\Model\TypeChecker;
use Phrototype\Utils;

class Factory {
	public static function create(
		array $fields = array(), $prototype = null
	) {
		if(!Utils::isHash($fields)) {
			$hash = [];
			array_map(function($v) use ($hash) {
				$hash[$v] = null;
			}, array_values($fields));
			$fields = $hash;
		}
		$obj = Prototype::create(
			$fields, $prototype, 'Phrototype\Model\Model'
		);
		return $obj;
	}

	public static function load(array $data = array(), $prototype = null) {
		$objects = [];
		foreach($data as $datum) {
			$objects[] = self::create($datum, $prototype);
		}
		return $objects;
	}
}