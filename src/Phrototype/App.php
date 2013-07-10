<?php

namespace Phrototype;

use Phrototype\Model;
use Phrototype\Renderer;
use Phrototype\Router;
use Phrototype\Writer;

class App {
	private $renderer;
	private $router;
	private $defaultRenderMethod;
	private $viewReader;

	/**
	 * constructor
	 * Takes a hash of args
	 * @param defaultRenderer string Overload the default render method
	 */
	public function __construct(array $args = null) {
		$this->renderer = new Renderer();
		$this->router = new Router();
		$this->viewReader = new Writer('views');

		if($args) {
			$this->defaultRenderMethod =
				  array_key_exists('defaultRenderer', $args) ?
				  $args['defaultRenderer']
				: 'mustache';
			// If the method isn't registered, load it up!
			$method = $this->defaultRenderMethod;
			if(!$this->renderer->methodExists($method)) {
				$this->renderer->registerExtension($this->method);
			}
		}
	}

	public function router() {
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
	 * @param data mixed The data to render; accepts objects
	 * @param view mixed The view to insert into the template
	 * @param template mixed The template to use, or the location of the template
	 * @param callback function A post-render callback
	 * @param method string The magic method to use if desired (view, edit, etc.)
	 */
	public function render(
		$renderer, $view, $data = null, $template = null, $callback = null
	) {
		return $this->renderer->method($renderer)->render($view, $data);
	}

	public function view($data) {
		$pairs = [];
		// Munge the data into key / value pairs
		foreach($data as $datum) {
			if(gettype($datum) === 'object'
				&& is_a($datum, '\Phrototype\Prototype')) {
				$datum = $datum->getProperties();
			}
			foreach($datum as $k => $v) {
				array_push($pairs, ['key' => $k, 'value' => print_r($v, true)]);
			}
		}
		return $this->render(
			'mustache',
			$this->viewReader->read('view.mustache'),
			$pairs
		);
	}

	public function add($validator) {
		return $this->render('html', $validator->html());
	}
}