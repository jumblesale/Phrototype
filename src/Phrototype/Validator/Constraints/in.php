<?php

namespace Phrototype\Validator\Constraints;

use Phrototype\Validator\iConstraint;

class in implements iConstraint {
	public function test($args, $v) {
		if(!($args && is_array($args))) return false;
		return in_array($v, $args);
	}
}