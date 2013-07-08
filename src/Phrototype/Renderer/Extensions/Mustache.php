<?php

namespace Phrototype\Renderer\Extensions;

use Phrototype\Renderer\iExtension;

class Mustache implements iExtension {
	private $engine;

	public function __construct() {
		$this->engine = new \Mustache_Engine();
	}

	public function test() {return class_exists('Mustache_Engine');}
	public function name() {return 'mustache';}
	public function load() {return [
		'renderer'	=> 'mustache',
	];}
	public function render() {
		return function($data, $tpl) {
			return $this->engine->render($tpl, $data);
		};
	}
}