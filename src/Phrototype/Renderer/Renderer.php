<?php

namespace Phrototype\Renderer;

use Phrototype\Logue;

class Renderer {
	private $method;
	private $callback;
	private $methods = [];

	private $defaultMethods = [
		'html'	=> [
			'mime'		=> 'text/html',
			'renderer'	=> 'text',
		],
		'json'	=> [
			'mime' 		=> 'application/json',
			'renderer'	=> 'json',
		],
		'text'	=> [
			'mime'		=> 'text/text',
			'renderer'	=> 'text',
		]
	];

	private $renderers = [];
	private function defaultRenderers() {
		// Add default renderers
		return [
			'text'	=> function($data) {return $data;},
			'json'	=> function($data) {return json_encode($data);},
		];
	}

	public function __construct() {
		if(!$this->methods) {
			// Default methods
			$this->registerDefaultMethods();
		}
	}

	public function registerDefaultMethods() {
		$methods = $this->defaultMethods;
		array_map(function($method, $methodDetails) {
			$this->methods[$method] = $methodDetails;
		}, array_keys($methods), array_values($methods));

		$this->renderers = $this->defaultRenderers();
	}

	public function getMethods() {return $this->methods;}

	public function registerExtension($obj) {
		if(gettype($obj) === 'string') {
			$obj = new $obj();
		}
		$class = get_class($obj);
		// This is hideous
		if(!in_array(
			'\Phrototype\Renderer\iExtension',
				class_implements(
					get_class($obj)
				))
		) {
			Logue::log(
				"Failed loading $class: does not implement iExtension interface",
				Logue::WARN
			);
			return false;
		}
	}

	public function method($method, $callback = null) {
		if(is_callable($method)) {
			$this->method = $method;
			return $this;
		}
		if(array_key_exists($method, $this->methods)) {
			$this->method = $method;
		} else {
			throw new \Exception("Unrecoginsed rendering method: $method");
		}
		return $this;
	}

	public function getRenderer() {
		$methodDetails = $this->methods[$this->method];
		return $this->renderers[$methodDetails['renderer']];
	}

	public function render($args = null) {
		if(!$this->method) {
			return $args;
		}
		if(is_callable($this->method)) {
			return call_user_func_array($this->method, func_get_args());
		}
		$data = func_get_args();
		if($this->callback) {
			$data = call_user_func_array($this->callback, func_get_args());
		}
		if(array_key_exists($this->method, $this->renderers)) {
			$data = call_user_func_array(
				$this->getRenderer(),
				$data
			);
		}
		return $data;
	}
}