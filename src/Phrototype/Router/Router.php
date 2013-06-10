<?php

namespace Phrototype\Router;

use Phrototype\Prototype;
use Phrototype\Router\Route;

class Router extends Prototype {

	private $routes;

	public function dispatch($verb, $path = null) {
		if(!array_key_exists($verb, $this->routes)) {
			return $this;
		}

		$verbRoutes = $this->routes[$verb];

		$params = [];
		preg_match_all('/^\/dog\/([^\/]+)$/', $path, $params);

		// Try to match the path to a route
		foreach($verbRoutes as $route => $routeObject) {
			$matches = [];
			if(
				preg_match_all(
					'/^\/dog\/\:([^\/]+)$/',
					$route, $matches
				)
			) {
				return call_user_method_array(
					'callback', $routeObject, [$params[1][0]]
				);
			}
		}
	}

	public function matches($verb, $path) {
		if(!array_key_exists($verb, $this->routes)) {
			return false;
		}
		$verbRoutes = $this->routes[$verb];
		return in_array($path, array_keys($verbRoutes));
	}

	public function route($verb, $path, $fn = null) {
		$route = Prototype::create(null, [
			'callback' => $fn, 'path' => $path,
		], 'Route');
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

		return call_user_method_array(
			'route', $this, $args
		);
	}

	public function get($args)    {return $this->_route('get',  func_get_args());}
	public function post($args)   {return $this->_route('post', func_get_args());}
	public function put($args)    {return $this->_route('put',  func_get_args());}
	public function delete($args) {return $this->_route('delete', func_get_args());}
}