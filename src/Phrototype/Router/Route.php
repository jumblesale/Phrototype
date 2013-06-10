<?php

namespace Phrototype\Router;

use Phrototype\Prototype;

class Route {
	private $callback;
	private $route;
	private $regex;

	function __construct($route, $callback = null) {
		$this->callback	= $callback;
		$this->route	= $route;

		$this->regex =
			'@^' . preg_replace('@:[^/]+@', '([^/]+)', $route) . '$@';
	}

	public function regex($v = null) {
		return $v ?
			  $this->regex = $v
			: $this->regex;
	}

	public function match($path) {
		return (bool)preg_match($this->regex, $path);
	}

	public function parsePath($path) {
		$argNames	= [];
		preg_match_all('@:([^/]+)@', $this->route, $argNames);
		// Ignore the indexes, just get the values
		$argNames	= $argNames[1];
		$matches	= [];
		preg_match_all($this->regex, $path, $matches);
		// The first element will be the whole string
		array_shift($matches);
		$args	= [];
		$i		= 0;
		foreach($argNames as $name) {
			$args[$name] = $matches[$i][0];
			$i += 1;
		}
		return $args;
	}

	public function callback($args = null) {
		return call_user_func_array(
			$this->callback,
			func_get_args()
		);
	}
}