<?php

namespace Phrototype\Prototype;

abstract class Prototype {
	private $properties;
	private $prototype;

	public function __construct($properties = []) {
		$this->properties = $properties;
	}

	public static function create($proto, $class, $args) {
		if(!class_exists($class)) {
			// Haha!
			eval("class $class extends Prototype {}");
		}
		$obj = new $class(array_merge($proto->getProperties(), $args));
		$obj->setPrototype($proto);

		return $obj;
	}

	public function __get($name) {
		if(array_key_exists($name, $this->properties)) {
			return $this->properties[$name];
		}
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
			// die
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