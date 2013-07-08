<?php

namespace Phrototype\Tests\Renderer;

use Phrototype\Renderer\Extensions\Mustache;
use Phrototype\Renderer\Renderer;

class MustacheTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		// Skip this if mustache isn't installed
		if(!class_exists('Mustache_Engine')) {
			$this->markTestSkipped(
				'Mustache is not available'
			);
		}
	}

	public function testDataCanBeRenderedInMustache() {
		$m = new \Mustache_Engine();
		$this->assertEquals(
			'Charles says "woof!"',
			$m->render('{{name}} says "{{sound}}!"',
			['name' => 'Charles', 'sound' => 'woof'])
		);
	}

	public function testMustacheExtensionCanBeLoaded() {
		$renderer = new Renderer();

		$this->assertTrue(
			$renderer->registerExtension(
				'Phrototype\Renderer\Extensions\Mustache'
			)
		);
		$this->assertTrue(
			in_array(
				'mustache',
				array_keys($renderer->getRenderers())
			)
		);

		return $renderer;
	}

	/**
	 * @depends testMustacheExtensionCanBeLoaded
	 */
	public function testExtensionCanRender($renderer) {
		$data = ['name' => 'Charles', 'judgement' => 'good',
				'reason' => 'Yes he is! Oh yes he is.',];
		$r = $renderer->method('mustache')->render(
			$data,
			'{{name}} is a {{judgement}} dog. {{reason}}'
		);

		$this->assertEquals(
			'Charles is a good dog. Yes he is! Oh yes he is.',
			$r
		);
	}
}