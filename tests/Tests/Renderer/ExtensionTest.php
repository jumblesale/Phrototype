<?php

namespace Phrototype\Tests\Renderer;

use Phrototype\Renderer\iExtension;
use Phrototype\Renderer\Renderer;

class ExtensionTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->renderer = new Renderer();
	}


	public function testRegisteringAnUnloadableExtensionDoesntRegister() {
		// No required methods
		$mock = $this->getMock('Extension');

		$this->assertFalse(
			$this->renderer->registerExtension($mock)
		);
	}
}