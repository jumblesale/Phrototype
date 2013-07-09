<?php

namespace Phrototype\Validator;

use \Phrototype\Curry;

class Field {
	private $name;
	private $constraints = [];
	private $messages;
	private $required = true;
	private $type;
	private $options = [];
	private $attributes = [];
	private $container = [];
	private $value = null;
	private $description;

	public function __construct($name = null, $type = null) {
		$this->name = $name;
		$this->type = $type;
	}

	// static factory method
	public static function create($name = null, $type = null) {
		return new Field($name, $type);
	}

	public function name() {
		return $this->name;
	}

	public function messages() {
		return $this->messages;
	}

	public function constraints() {
		return $this->constraints;
	}

	public function attributes(array $v = null) {
		if($v !== null) {
			$this->attributes = $v;
			return $this;
		}
		return $this->attributes;
	}

	public function value($v = null) {
		if($v !== null) {
			$this->value = $v;
			return $this;
		}
		return $this->value;
	}

	public function description($v = '') {
		if($v) {
			$this->description = $v;
			return $this;
		}
		return $this->description;
	}

	public function required($v = null) {
		if($v !== null) {
			$this->required = $v;
			return $this;
		}
		return $this->required;
	}

	public function options(array $v = null) {
		if($v !== null) {
			$this->options = $v;
			return $this;
		}
		return $this->options;
	}

	public function type($v = null) {
		if($v) {
			$this->type = $v;
			return $this;
		}
		return $this->type;
	}

	public function container(
		$tag = null,
		array $attributes = array()
	) {
		if(!$tag) {
			return $this->container ?: null;
		}
		$this->container = [
			'tag' => $tag,
			'attributes' => $attributes,
		];
		return $this;
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