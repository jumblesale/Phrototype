<?php

namespace Phrototype\Model;

use Phrototype\Prototype;
use Phrototype\Utils;
use Phrototype\Writer;

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
			$fields, $prototype, 'Phrototype\Model'
		);
		return $obj;
	}

	public static function load($data = array(), $prototype = null) {
		$objects = [];
		if(is_string($data)) {
			$writer = new Writer();
			// Assume json for now
			$json = json_decode($writer->read($data), true);
			if(!$json) {
				throw new \Exception("Could not load JSON from $data");
			}
			$data = $json;
		}
		foreach($data as $datum) {
			$objects[] = self::create($datum, $prototype);
		}
		return new Collection($objects);
	}
}