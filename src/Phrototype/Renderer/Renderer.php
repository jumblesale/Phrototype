<?php

namespace Phrototype\Renderer;

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

	public function __construct() {
		if(!$this->methods) {
			// Default methods
			$this->registerMethods($this->defaultMethods);
		}
	}

	public function registerMethods(array $methods = array()) {
		array_map(function($method, $methodDetails) {
			$this->methods[$method] = $methodDetails;
		}, array_keys($methods), array_values($methods));

		// Add default renderers
		$this->renderers = [
			'text'	=> function($data) {return $data;},
			'json'	=> function($data) {return json_encode($data);},
		];
	}

	public function getMethods() {return $this->methods;}

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