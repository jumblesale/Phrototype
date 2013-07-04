<?php

namespace Phrototype;

use Phrototype\Validator\Field;

class Validator {
	private $fields;
	private $groups;
	private $currentGroup;

	public function group($name) {
		$this->groups[$name] = '';
		return $this;
	}

	public function groups() {
		return $this->groups;
	}

	public function fields() {
		return $this->fields;
	}

	public function field($name) {
		if($this->currentGroup) {
			$this->groups[$name][] = $name;
		}
		$field = new Field($name);
		$this->fields[$name] = '';
		return $field;
	}
}