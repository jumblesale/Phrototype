<?php

namespace Phrototype\Validator;

use \Phrototype\Curry;

class Field {
	private $name;
	private $constraints;

	public function __construct($name = null) {
		$this->name = $name;
	}

	public function constraints() {
		return $this->constraints;
	}

	public function constrain($name, $values = null, $message = null) {
		$this->constraints[$name] = '';
		return $this;
	}

	public function curryConstraint($class, $values = null) {
		// Load the constraint
		$class = '\Phrototype\Validator\Constraints\\' . $class;
		if(!class_exists($class)) {
			throw new Excpetion("No constraint found: $class");
		}
		$constraint = new $class();
		/* Curry the method
		*  the last argument will always be what's being tested */
		$args = array_merge([$values], [Curry\Bind::…()]);
		// The first argument has to be the object and the method name
		array_unshift($args, [$constraint, 'test']);

		$fn = call_user_func_array(
			'\Phrototype\Curry\Bind::partial',
			$args
		);
		
		return $fn;
	}
}