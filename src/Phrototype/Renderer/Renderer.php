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
			'mime'		=> 'text/plain',
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
		$this->renderers = $this->defaultRenderers();

		$methods = $this->defaultMethods;
		array_map(function($method, $methodDetails) {
			$this->registerMethod($method, $methodDetails);
		}, array_keys($methods), array_values($methods));
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

		$name = $obj->name();
		if($this->registerMethod(
			$name, $obj->load(), $obj->renderer()
		)) {
			Logue::log("Successfully loaded renderer extension: $name", Logue::INFO);
		}
	}

	public function registerMethod($name, $details, $renderer = null) {
		if(!array_key_exists('renderer', $details)) {
			throw new \Exception("No renderer provided for $name");
		}
		$this->methods[$name] = $details;
		$rendererName = $details['renderer'];
		// Check if this method references an existing renderer
		if(!array_key_exists($rendererName, $this->renderers)) {
			if(!$renderer) {
				throw new \Exception("$name requires an unregistered renderer but does not provide one");
			}
			if(gettype($renderer) === 'object'
				&& get_class($renderer) === 'Closure') {
				// Add the callback to the list of renderers
				$this->renderers[$rendererName] = $renderer;
			} else {
				throw new \Exception("$name provides a renderer which is not a valid callback");
			}
		}
		return true;
	}

	public function method($method, $callback = null) {
		if(is_callable($method)) {
			$this->method = $method;
			return $this;
		}
		if(array_key_exists($method, $this->methods)) {
			$this->method = $method;
		} else {
			throw new \Exception("Unrecognised rendering method: $method");
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