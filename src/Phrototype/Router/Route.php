<?php

namespace Phrototype\Router;

use Phrototype\Prototype;

class Route extends Prototype {
	public $fn;
	
	public function dispatch() {
		return $this->fn();
	}
}