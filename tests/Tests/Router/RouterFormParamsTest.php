<?php

namespace Phrototype\Tests\Router;

use Phrototype\Router;

class RouterFormParamsTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->router = new Router();
	}

	public function testGetParametersAreAppliedToFunction() {
		$_GET['name'] = 
		$this->assertEquals(
			'pug is the best kind of dog',
			$this->fixture->get('/dog', function($name = null) {
				return "$dog is the best kind of dog";
			})->dispatch('get', '/dog/pug')
		);
	}
}