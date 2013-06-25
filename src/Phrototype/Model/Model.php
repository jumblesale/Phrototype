<?php

namespace Phrototype\Model;

use Phrototype\Prototype;
use Phrototype\Model\TypeChecker;

class Model extends Prototype {
	public function load(array $data = array()) {
		$objs = [];
		foreach($data as $datum) {
			$objs[] = Prototype::create($datum, $this, get_class($this));
		}

		return $objs;
	}

	public function fields() {
		return ['age' => null];
	}
}