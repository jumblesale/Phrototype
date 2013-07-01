<?php

namespace Phrototype\Model;

use Phrototype\Prototype;
// use Phrototype\Model\TypeChecker;
use Phrototype\Writer;

class Model extends Prototype {
	public function save($filename, $directory) {
		$writer = new Writer($directory);
		$writer->write($filename, '');
	}

	public function load($data = null) {
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