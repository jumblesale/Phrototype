<?php

namespace Phrototype\Model;

use Phrototype\Prototype;
use Phrototype\Model\TypeChecker;

class Model extends Prototype {
	public function load(array $data = array()) {
		$objs = [];
		foreach($data as $datum) {
			$objs[] = Prototype::create($data, $this, get_class($this));
		}

		return $objs;
	}
}