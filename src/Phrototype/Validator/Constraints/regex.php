<?php

namespace Phrototype\Validator\Constraints;

use Phrototype\Validator\iConstraint;

class regex implements iConstraint {
	public function test($args, $v) {
		if(!$args) return false;
		return (bool)(preg_match($args, $v));
	}
}