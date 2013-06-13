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
		
		// Create a regex to find all instances of :something
		$regex = preg_replace('@:[^/]+@', '([^/]+)', $route);
		// Strip out optional variables (?variable)
		// The leading / cannot be relied on, for example:
		// 		/app/:action/?id
		//		/app/view - should match
		$regex = preg_replace('@/\?[^/]+@', '/?([^/]+)?', $regex);
		// Strip the starting slash from the route
		if($regex{0} === '/') {$regex = substr($regex, 1);}
		// and include it as an optional
		$this->regex = '@^/?' . $regex . '/?$@';
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
		preg_match_all('@[:\?]([^/]+)@', $this->route, $argNames);
		// Ignore the indexes, just get the values
		$argNames	= $argNames[1];
		$matches	= [];
		preg_match_all($this->regex, $path, $matches);
		// The first element will be the whole string
		array_shift($matches);
		$args	= [];
		$i		= 0;
		array_map(function($name) use($matches, &$args, &$i) {
			$value = $matches[$i][0];
			$args[$name] = $value;
			$i += 1;
		}, $argNames);
		return $args;
	}

	public function callback($args = null) {
		return call_user_func_array(
			$this->callback,
			func_get_args()
		);
	}
}