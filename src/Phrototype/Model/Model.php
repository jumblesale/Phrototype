<?php

namespace Phrototype\Model;

use Phrototype\Prototype;

class Model {
	private $fields;

	public static function create(array $prototype = array()) {
		$obj = new Prototype();
		$fields = [];
		foreach($prototype as $name => $field) {
			$fields[$name] = $field;
		}
		$obj->fields = $fields;
		echo "\n\n"; print_r($obj->fields); echo "\n\n";
	}

	public function load(array $data = array()) {
		
	}
}