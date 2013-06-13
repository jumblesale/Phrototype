<?php

namespace Phrototype\Tests\Router;

use Phrototype\Router\Route;

class RouteTest extends \PHPUnit_Framework_TestCase {
	public function testRegexIsGeneratedCorrectly() {
		$route = new Route('/app/:action/:id');
		$regex = $route->regex();
		$this->assertTrue(
			(bool)preg_match($regex, '/app/view/3')
		);
		$this->assertFalse(
			(bool)preg_match($regex, '/ohsnapp/delete/3')
		);
	}

	public function testPathIsGeneratedWithOptionalVariables() {
		$route = new Route('app/:action/?id/constant/:variable');
		$regex = $route->regex();
		$this->assertTrue(
			(bool)preg_match($regex, '/app/view/3/constant/variable')
		);
		$this->assertTrue(
			(bool)preg_match($regex, '/app/delete/constant/variable')
		);
		$this->assertFalse(
			(bool)preg_match($regex, '/app/delete/3/')
		);
	}

	public function testPathCanBeParsed() {
		$route = new Route('/app/:action/:id');
		$args = $route->parsePath('/app/view/3');
		$this->assertEquals(
			[
				'action'	=> 'view',
				'id'		=> 3,
			],
			$args
		);
	}

	public function testPathWithOptionalParametersCanBeParsed() {
		$route = new Route('/app/:action/?id');
		$args = $route->parsePath('/app/view/3');
		$this->assertEquals(
			[
				'action'	=> 'view',
				'id'		=> 3,
			],
			$args
		);
		$args = $route->parsePath('/app/view');
		$this->assertEquals(
			[
				'action'	=> 'view',
				'id'		=> null,
			],
			$args
		);
	}

	public function testPathCanContainSpecialCharacters() {
		$route = new Route('/send/:email');
		$this->assertEquals(
			['email' => 'sharmer.palmer@llamafarmer.org'],
			$route->parsePath('/send/sharmer.palmer@llamafarmer.org')
		);
	}

	public function testCanSetCallback() {
		$route = new Route('/home', function($a, $b) {return $a + $b;});
		$this->assertEquals(
			3,
			$route->callback(1, 2)
		);
	}
}