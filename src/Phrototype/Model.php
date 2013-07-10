<?php

namespace Phrototype;

use Phrototype\Prototype;
use Phrototype\Writer;
use Phrototype\Utils;

class Model extends Prototype {
	public function save($filename, $directory) {
		touch(
			Utils::slashify(Utils::getDocumentRoot() . $directory) . $filename
		);
	}

	public function load($data = null) {
		if(!is_array($data)) {
			return $data;
		}
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