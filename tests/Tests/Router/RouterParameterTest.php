<?php

namespace Phrototype\Tests\Prototype;

use Phrototype\Router\Router;

class RouterParameterTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->fixture = new Router();
	}

	public function testParametererisedRoutesGetMatched() {
		$this->fixture->get('/delete/:id');
		$this->assertTrue(
			$this->fixture->matches('get', '/delete/3')
		);
		$this->assertFalse(
			$this->fixture->matches('get', '/view/3')
		);
	}

	public function testSingleParameterIsSet() {
		return;
		$this->assertEquals(
			'pug is the best kind of dog',
			$this->fixture->get('/dog/:type', function($self, $dog) {
				return "$dog is the best kind of dog";
			})->dispatch('get', '/dog/pug')
		);
	}

	public function testManyParametersAreSet() {
		$this->assertEquals(
			'Charles the pug says woof',
			$this->fixture->get(
				'/dog/:name/breed/:type/says/:says',
				function($name, $type, $says) {
					return "$name the $type says $says";
				}
			)->dispatch('get', '/dog/Charles/breed/pug/says/woof')
		);
	}
}