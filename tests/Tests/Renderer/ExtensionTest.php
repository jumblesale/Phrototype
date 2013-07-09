<?php

namespace Phrototype\Tests\Renderer;

use Phrototype\Renderer\iExtension;
use Phrototype\Renderer\Renderer;

class ExtensionTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->renderer = new Renderer();
	}

	public function testRegisterMethod() {
		$method = [
			'renderer'	=> 'json'
		];

		$this->renderer->registerMethod('test', $method);

		$r = $this->renderer->getMethods()['test'];

		$this->assertEquals($method, $r);
	}

	public function testRegisteringAnUnloadableExtensionDoesntRegister() {
		// No required methods
		$mock = $this->getMock('Extension');

		$this->assertFalse(
			$this->renderer->registerExtension($mock)
		);
	}

	public function testRegisterMethodWithCustomRenderer() {
		$method = [
			'renderer'	=> 'dog'
		];

		$this->renderer->registerMethod('dog', $method, function($data) {
			return preg_replace(
				'/corgi/', 'pug', $data
			);
		});

		$r = $this->renderer->method('dog')->render(
			'corgi is the best dog'
		);

		$this->assertEquals('pug is the best dog', $r);
	}

	public function testAutoLoadFailsWithInvalidMethod() {
		$this->assertFalse(
			$this->renderer->method('notamethod')
		);
	}
}