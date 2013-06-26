<?php

namespace Phrototype\Model;

use Phrototype\Prototype;
// use Phrototype\Model\TypeChecker;

class Model extends Prototype {
	public function load(array $data = array()) {
		$objs = [];
		foreach($data as $datum) {
			$objs[] = $this->forge($datum);
		}

		return $objs;
	}

	public function forge(array $data = array()) {
		return Prototype::create($data, $this, get_class($this));
	}
}