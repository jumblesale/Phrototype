<?php

namespace Phrototype\Validator;

interface iConstraint {
	public function test($args, $v);
}