<?php

namespace Phrototype\Validator\Constraints;

use Phrototype\Validator\iConstraint;

class range implements iConstraint {
	public function test($args, $v) {
		if(!is_numeric($v)) {
			return false;
		}
		if(count($args) == 1) {
			return $v <= $args[0];
		}
		return ($v >= $args[0] && $v <= $args[1]);
	}
}