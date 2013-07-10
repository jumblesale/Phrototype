<?php

namespace Phrototype\Tests\Router;

use Phrototype\Router;

class RoutingTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->fixture = new Router();
	}

	public function testAddingARoute() {
		$route = $this->fixture->route('get', '/home');

		$this->assertTrue(
			array_key_exists('/home',
				$this->fixture->getRoutes()['get'])
		);

		return $route;
	}

	/**
	 * @depends testAddingARoute
	 */
	public function testDeletingRoute($route) {
		$route->removeRoute('get', '/home');

		$this->assertFalse(
			array_key_exists('/home',
				$route->getRoutes()['get'])
		);
	}

	public function testVerbAccessorsProduceRoutes() {
		$verbs = ['get', 'put', 'post', 'delete'];

		array_map(function($verb) {
			$this->fixture->$verb('/home');
			$this->assertTrue(
				$this->fixture->matches($verb, '/home')
			);
		}, $verbs);
	}

	public function testTopLevelRoute() {
		$home = $this->fixture->get('/home');

		$this->assertTrue(
			$home->matches('get', '/home')
		);
		$this->assertFalse(
			$home->matches('get', '/notapage')
		);
	}

	public function testSubsequentRoutesOverridePreviousOnes() {
		$home = $this->fixture->get('/home', function() {return false;});

		$this->assertFalse($this->fixture->getRoute('get', '/home')->callback());

		$home2 = $this->fixture->get('/home', function() {return true;});

		$this->assertTrue($this->fixture->getRoute('get', '/home')->callback());
	}
}