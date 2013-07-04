<?php

namespace Phrototype\Validator\Constraints;

use Phrototype\Validator\iConstraint;

class length implements iConstraint {
	public function test($args, $v) {
		if(!$v) return false;
		$length = strlen($v);
		if(count($args) == 1) {
			return (bool)($length <= $args[0]);
		}
		return (bool)($length >= $args[0] && $length <= $args[1]);
	}
}