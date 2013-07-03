<?php

namespace Phrototype;

use Phrototype\Model;
use Phrototype\Renderer\Renderer;
use Phrototype\Router\Router;

class App {
	private $renderer;
	private $router;
	private $defaultRenderMethod;

	/**
	 * constructor
	 * Takes a hash of args
	 * @param defaultRenderer string Overload the default render method
	 */
	public function __construct(array $args = null) {
		$this->renderer = new Renderer();
		$this->router = new Router();

		if($args) {
			$this->defaultRenderMethod =
				  array_key_exists('defaultRenderer', $args) ?
				  $args['defaultRenderer']
				: 'mustache';
			// If the method isn't register, load it up!
			$method = $this->defaultRenderMethod;
			if(!$this->renderer->methodExists($method)) {
				$this->renderer->registerExtension($this->$method);
			}
		}
	}

	public function route() {
		return $this->router;
	}

	public function renderer() {
		return $this->renderer;
	}

	public function defaultRenderer($v) {
		return $v ?
			  $this->defaultRenderMethod = $v
			: $this->defaultRenderMethod;
	}

	/**
	 * render
	 * @param renderer string The name of the renderer to use (json, text, etc.)
	 * @param view mixed The view to insert into the template
	 * @param data mixed The data to render; accepts objects
	 * @param template mixed The template to use, or the location of the template
	 * @param callback function A post-render callback
	 * @param method string The magic method to use if desired (view, edit, etc.)
	 */
	public function render(
		$renderer, $view, $data = null, $template = null, $callback = null
	) {
		$this->renderer->registerExtension(
			'Phrototype\Renderer\Extensions\Mustache'
		);
		return $this->renderer->method('mustache')->render(
			'<ul>{{#.}}<li>{{title}}: {{content}}</li>{{/.}}</ul>', $data
		);
	}

	public function view() {
		return call_user_method_array('render', $this, func_get_args());
	}
}