<?php

namespace Phrototype\Validator;

use \Phrototype\Curry;

class Field {
	private $name;
	private $constraints;
	private $messages;

	public function __construct($name = null) {
		$this->name = $name;
	}

	public function messages() {
		return $this->messages;
	}

	public function constraints() {
		return $this->constraints;
	}

	public function constrain($name, $values = null, $message = null) {
		$this->constraints[$name]['fn'] = $this->curryConstraint(
			$name, $values
		);
		if(!$message) {
			$message = "$name constraint failed";
		}
		$this->constraints[$name]['message'] = $message;
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

	public function test($name, $value) {
		return (bool)$this->constraints[$name]['fn']($value);
	}

	public function validate($value) {
		$success = true;
		$messages = [];
		foreach(array_keys($this->constraints) as $constraint) {
			if(!$this->test($constraint, $value)) {
				$success = false;
				array_push(
					$messages,
					$this->constraints[$constraint]['message']
				);
			}
		}
		$this->messages = $messages;
		return $success;
	}
}