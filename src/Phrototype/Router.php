<?php

namespace Phrototype;

use Phrototype\Prototype;
use Phrototype\Router\Route;
use Phrototype\Router\RouteParser;

class Router extends Prototype {

	private $routes;
	private $routeParser;

	public function __construct() {
		$this->routeParser = new RouteParser($_SERVER);
	}

	public function dispatch($verb, $path = null) {
		if(!array_key_exists($verb, $this->routes)) {
			return $this;
		}

		$verbRoutes = $this->routes[$verb];

		// Try to match the path to a route
		$return;
		$this->findRoute(
			$path, $verbRoutes,
			function($route) use ($path, &$return) {
				$args = $route->parsePath($path);
				$return = call_user_func_array(
					[$route, 'callback'],
					$args
				);
			}
		);
		return $return;
	}

	public function matches($verb, $path) {
		$routes = $this->routes[$verb];

		if(in_array($path, array_keys($routes))) {
			return true;
		}
		
		return $this->findRoute($path, $routes) !== false;
	}

	public function findRoute($path, $routes, $fn = null) {
		foreach($routes as $route => $routeObject) {
			if($routeObject->match($path)) {
				return $fn ?
					   $fn($routeObject)
					 : $routeObject;
			}
		}
		return false;
	}

	public function route($verb, $path, $fn = null) {
		$route = new Route($path, $fn);
		$this->routes[$verb][$path] = $route;

		return $this;
	}

	public function removeRoute($verb, $route) {
		if(!array_key_exists($verb, $this->routes)) {
			return $this;
		}
		if(array_key_exists($route, $this->routes[$verb])) {
			unset($this->routes[$verb][$route]);
		}
		return $this;
	}

	public function getRoutes() {
		return $this->routes;
	}

	public function getRoute($verb, $route) {
		return $this->routes[$verb][$route];
	}

	// Utility method for invoking route through a verb
	private function _route($verb, $args) {
		$args = ($args && is_array($args)) ?
			  $args
			: array($args);
		array_unshift($args, $verb);

		return call_user_func_array(
			[$this, 'route'], $args
		);
	}

	public function get($args)    {return $this->_route('get',  func_get_args());}
	public function post($args)   {return $this->_route('post', func_get_args());}
	public function put($args)    {return $this->_route('put',  func_get_args());}
	public function delete($args) {return $this->_route('delete', func_get_args());}
}