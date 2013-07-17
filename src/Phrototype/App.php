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
	private $appReader;
	private $viewReader;
	private $routeParser;

	/**
	 * constructor
	 * Takes a hash of args
	 * @param defaultRenderer string Overload the default render method
	 * @param root string The root directory of the app
	 */
	public function __construct(array $args = array()) {
		$this->renderer = new Renderer();
		$this->router = new Router();
		$this->routeParser = new Router\RouteParser($_SERVER);
		$this->appReader = array_key_exists('root', $args) ?
			  new Writer(Utils::slashify($args['root']))
			: new Writer(Utils::getDocumentRoot());
		$this->defaultRenderMethod = 'html';

		if($args) {
			$this->defaultRenderMethod =
				  array_key_exists('defaultRenderer', $args) ?
				  $args['defaultRenderer']
				: 'html';
		}
	}

	/**
	 * read
	 * Read a file
	 *
	 * @param file string Location of the file relevant to the document root
	 * @return string The contents of the file
	 */
	public function read($file) {
		return $this->appReader->read($file);
	}

	public function router() {
		return $this->router;
	}

	public function renderer() {
		return $this->renderer;
	}

	public function request() {
		return $this->routeParser;
	}

	public function defaultRenderer($v) {
		return $v ?
			  $this->defaultRenderMethod = $v
			: $this->defaultRenderMethod;
	}

	public function import($libs) {
		return $this->renderer->importer()->import($libs);
	}

	/**
	 * redirect
	 * Redirect the request to a different handler
	 * NOTE: this will always result in a GET request
	 * @param path string The path to redirect to
	 * @return null die()s on completion
	 */
	public function redirect($path) {
		Logue::Log("Redirecting to get:$path", Logue::INFO);
		header('Location: ' . $path, true, 302);
		die();
	}

	// Dispatch the request to the registered route
	public function go($verb = null, $path = null) {
		$verb = $verb ?: $this->routeParser->verb();
		$path = $path ?: $this->routeParser->path();
		Logue::Log("Dispatching to $verb:$path", Logue::INFO);
		if($this->router->matches($verb, $path)) {
			return $this->router->dispatch(
				$verb,
				$path
			);
		} else {
			Logue::log("No path registered for $path, 404ing", Logue::WARN);
			return $this->fourohfour();
		}
	}

	private function fourohfour() {echo 'my spleen!'; return false;}

	/**
	 * render
	 * @param view mixed The view to insert into the template
	 * @param data mixed The data to render; accepts objects
	 * @param method string The name of the renderer to use (json, text, etc.)
	 * @param template mixed The template to use, or the location of the template
	 * @param callback function A post-render callback
	 * @param method string The magic method to use if desired (view, edit, etc.)
	 */
	public function render(
		$view, $data = null, $method = null, $template = null, $callback = null
	) {
		$method = $method === null ? $this->defaultRenderMethod : $method;
		if(!$this->renderer->method($method)) {
			throw new \Exception("Tried to render with $method but it was not "
				. "available and could not be autoloaded.");
		}
		return $this->renderer->method($method)->render($view, $data);
	}
}