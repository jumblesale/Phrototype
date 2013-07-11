<?php

namespace Phrototype;

use Phrototype\Prototype;
use Phrototype\Writer;
use Phrototype\Utils;

class Model extends Prototype {
	public function save($location) {
		$w = new Writer();
		$w->write($location, json_encode($this->toArray()));
	}

	public function toArray() {
		$array = [];
		foreach($this->properties as $name => $value) {
			$array[$name] = $value;
		}
		return $array;
	}

	public static function load($data = null) {
		if(is_string($data)) {
			$w = new Writer();
			$json;
			try {
				$json = $w->read($data);
			} catch(\Exception $e) {
				throw new \Exception("Unable to load model from $data,"
					. " location in unreachable with " . $e->getMessage());
			}
			$json = json_decode($json, true);
			if(!$json) {
				throw new \Exception("JSON in $data is malformed");
				return false;
			}
			return self::forge($json);
		}
		$obj = [];
		foreach($data as $name => $value) {
			$obj[$name] = $value;
		}

		return self::forge($obj);
	}

	public static function forge(array $data = array()) {
		return Prototype::create($data, null, '\Phrototype\Model');
	}
}