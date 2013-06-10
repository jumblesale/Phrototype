<?php

namespace Phrototype\Tests\Prototype;

use Phrototype\Router\Router;

class RouterParameterTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->fixture = new Router();
	}

	public function testSingleParameterIsSet() {
		$this->assertEquals(
			'pug is the best kind of dog',
			$this->fixture->get('/dog/:type', function($self, $dog) {
				return "$dog is the best kind of dog";
			})->dispatch('get', '/dog/pug')
		);
	}
}