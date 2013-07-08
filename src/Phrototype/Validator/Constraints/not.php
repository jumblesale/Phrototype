<?php

namespace Phrototype\Validator\Constraints;

use Phrototype\Validator\iConstraint;

class not implements iConstraint {
	public function test($args, $v) {
		if(!($args && is_array($args))) return true;
		return !in_array($v, $args);
	}
}