<?php

namespace Phrototype\Tests\Prototype;

use Phrototype;

class RouterParameterTest extends \PHPUnit_Framework_TestCase {
	public function testTopLevelPathIsRouted() {
		return;
		$router = new Router();

		$this->assertEquals(
			'pug is the best kind of dog',
			$router->get('/dog/:type', function($name) {
				echo "$dog is the best kind of dog";
			})->dispatch('/dog/pug')
		);
	}
}