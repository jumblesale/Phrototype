<?php

namespace Phrototype\Validator;

/**
 * ArraySyntaxParser
 * Converts HTML field names to array indexes
 * user[details][name] will look for
 * $array['user']['details']['name']
 */
class ArraySyntaxParser {
	private $value;
	private $path;
	private $root;

	public function value() {
		return $this->value;
	}

	public function path() {
		return $this->path;
	}

	public function root() {
		return $this->root;
	}

	public function parse(array $array, $name) {
		$this->path = null;
		$this->value = null;
		$this->root = null;
		if(strpos($name, '[') === false) {
			$this->value = array_key_exists($name, $array) ?
				  $array[$name]
				: null;
			return $this;
		}
		$root = $this->setRoot($name);
		$keys = [];
		$value = $array[$root];
		preg_match_all('/\[([^\]]+)\]/', $name, $keys);
		$path = [];
		foreach($keys[1] as $entry) {
			$value = $value[$entry];
			$path[] = $entry;
		}
		$this->path = $path;
		$this->value = $value;
		return $this;
	}

	/**
	 * getVar
	 * Given a name in array notation, returns the parent variable
	 * user[name] => user
	 */
	public function setRoot($root) {
		$name = [];
		preg_match('/^([^\[]*)/', $root, $name);
		$this->root = array_key_exists(1, $name) ?
			  $name[1]
			: $root;
		return $this->root;
	}

	/**
	 * toArray
	 * If a path has been set, turn it into a nested hash
	 * ['user', 'details', 'name'] becomes
	 * ['user' => ['details' => ['name' => $value]]]
	 */
	public function toArray() {
		if(!$this->path) {
			return $this->value;
		}
		$path = $this->path;
		$value = $this->value;
		$fn = function($fn, $array = null, $value)  {
			if(!$array) {
				return $value;
			}
			$key = array_shift($array);
			return [$key => $fn($fn, $array, $value)];
		};
		$array = $fn($fn,  $path, $value);
		return $array;
	}
}