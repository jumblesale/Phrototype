<?php

namespace Phrototype\Validator\Constraints;

use Phrototype\Validator\iConstraint;

class matches implements iConstraint {
	public function test($args, $v) {
		return (bool)($args === $v);
	}
}