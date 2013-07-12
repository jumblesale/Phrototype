<?php

namespace Phrototype;

use Phrototype\Logue;
use Phrototype\Renderer\ExtensionRegisterer;
use Phrototype\Renderer\LibIncluder;

class Renderer {
	private $method;
	private $extensionRegisterer;
	private $methods = [];

	private $template;
	private $contentKey = 'content';

	private $importer;

	private $defaultMethods = [
		'html'	=> [
			'renderer'	=> 'text',
		],
		'json'	=> [
			'renderer'	=> 'json',
		],
		'text'	=> [
			'renderer'	=> 'text',
		],
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
		$this->registerDefaultMethods();
		$this->extensionRegisterer = new ExtensionRegisterer();
		$this->importer = new LibIncluder();
		$this->templateData = Model::forge();
	}

	public static function create() {
		return new Renderer();
	}

	public function template($t = null, array $v = null) {
		if($t) {
			$this->template = $t;
			if($v) {
				$this->templateData = Model::forge($v);
			}
			return $this;
		}
		return $this->templateData;
	}

	public function getTemplate() {
		return $this->template;
	}

	public function contentKey($v = null) {
		if($v) {
			$this->contentKey = $v;
			return $this;
		}
		return $this->contentKey;
	}

	public function importer() {
		return $this->importer;
	}

	public function registerDefaultMethods() {
		$this->renderers = $this->defaultRenderers();

		$methods = $this->defaultMethods;
		array_map(function($method, $methodDetails) {
			$this->registerMethod($method, $methodDetails);
		}, array_keys($methods), array_values($methods));
	}

	public function getMethods() {return $this->methods;}
	public function getRenderers() {return $this->renderers;}

	public function methodExists($method) {
		$methods = $this->getMethods();
		return in_array($method, array_keys($methods));
	}

	public function registerExtension($obj) {
		if($obj = $this->extensionRegisterer->loadExtension($obj)) {
			if($this->registerMethod(
					$obj->name(),
					$obj->load(),
					$obj->render()
			)) {
				Logue::log("Successfully loaded renderer extension: " . $obj->name(), Logue::INFO);
				return true;
			} else {
				Logue::log("Failed to load extension: " . $obj->name(), Logue::WARN);
				return false;
			}
		}
		return false;
	}

	public function registerMethod(
		$name, array $details, $renderer = null
	) {
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

	public function method($method) {
		if(is_callable($method)) {
			$this->method = $method;
			return $this;
		}
		if(array_key_exists($method, $this->methods)) {
			$this->method = $method;
		} else {
			// Try to autoload the method
			if(!$this->registerExtension($method)) {
				return false;
			} else {
				$this->method = $method;
			}
		}
		return $this;
	}

	public function getRenderer() {
		$methodDetails = $this->methods[$this->method];
		return $this->renderers[$methodDetails['renderer']];
	}

	private function __render($args) {
		// No render has been passed, just dump the data
		if(!$this->method) {
			return $args;
		}
		// The method is an anonymous function so we can just call that
		if(is_callable($this->method)) {
			return call_user_func_array($this->method, func_get_args());
		}
		$data = func_get_args();
		// A method has been set and it's a stored renderer
		if(array_key_exists($this->method, $this->getMethods())) {
			$data = call_user_func_array(
				$this->getRenderer(),
				$data
			);
		}
		return $data;
	}

	public function render($args) {
		$args = func_get_args();
		$content = call_user_func_array([$this, '__render'], $args);
		if($this->template) {
			$templateArgs = array_merge(
				$this->templateData->getProperties(),
				[$this->contentKey() => $content]
			);
			return $this->__render(
				$this->template,
				$templateArgs
			);
		}
		return $content;
	}
}