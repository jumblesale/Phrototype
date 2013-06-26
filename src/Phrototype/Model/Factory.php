<?php

namespace Phrototype\Model;

use Phrototype\Prototype;
use Phrototype\Model\TypeChecker;

class Factory {
	public static function create(
		array $fields = array(), $prototype = null
	) {
		$obj = Prototype::create(
			$fields, $prototype, 'Phrototype\Model\Model');
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