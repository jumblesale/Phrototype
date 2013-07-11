<?php

namespace Phrototype;

class Prototype {
	protected $properties = [];
	protected $prototype;

	public function __construct($properties = []) {
		$this->properties = $properties;
	}

	public static function create($args = [], $proto = null, $class = null) {
		if($class && !class_exists($class)) {
			// Haha!
			eval("class $class extends Phrototype\Prototype {}");
		}
		$properties = [];
		$properties = $proto ?
			  array_merge($proto->getProperties(), $args)
			: $args;
		
		$obj = $class ? new $class($properties) : new Prototype($properties);
		$obj->setPrototype($proto);

		return $obj;
	}

	public function __clone() {
		return $self::create([], $this);
	}

	public function __get($name) {
		if(array_key_exists($name, $this->properties)) {
			return $this->properties[$name];
		}
	}

	public function __isset($name) {
		return array_key_exists($name, $this->properties);
	}

	public function __set($name, $value) {
		if(gettype($value) === 'object'
			&& get_class($value) === 'Closure') {
			return $this->addProperty($name, $value);
		} else {
			return $this->addProperty($name, $value);
		}
	}

	public function __call($name, $args) {
		if(!array_key_exists($name, $this->properties)) {
			if(!$this->getPrototype()) {
				return null;
			} else {
				$fn = $this->getPrototype()->$name;
			}
		} else {
			$fn = $this->properties[$name];
		}
		array_unshift($args, $this);
		if(!is_callable($fn)) {
			throw new \Exception("Cannot call $name");
		}
		return call_user_func_array($fn->bindTo($this, $this), $args);
	}

	public function setPrototype($proto) {
		$this->prototype = $proto;
	}

	public function getPrototype() {
		return $this->prototype;
	}

	public function getProperties() {
		return $this->properties;
	}

	public function addProperty($name, $value) {
		$this->properties[$name] = $value;
	}
}