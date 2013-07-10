<?php

namespace Phrototype\Tests\Renderer;

use Phrototype\Renderer;

class RendererTest extends \PHPUnit_Framework_TestCase {
	private static $contacts;

	public function setUp() {
		$this->fixture = new Renderer();
	}

	public function testHtmlJsonAndTextAreAlwaysRegistered() {
		$this->assertTrue($this->fixture->methodExists('json'));
		$this->assertTrue($this->fixture->methodExists('text'));
		$this->assertTrue($this->fixture->methodExists('html'));
	}

	public function testCanRenderWithoutSettingAMethod() {
		$this->assertEquals(
			'hello, wormold!',
			$this->fixture->render('hello, wormold!')
		);
	}

	public function testCanRenderWithFunctionAsMethod() {
		$o = $this->fixture->method(
			function($greeting) {return "$greeting, wormold!";}
		)->render('hello');

		$this->assertEquals('hello, wormold!', $o);
	}

	public function testCanRenderJson() {
		$data = ['hello' => 'wormold'];
		$this->assertEquals(
			json_encode($data),
			$this->fixture->method(
				'json'
			)->render($data)
		);
	}

	public function contacts() {
		return [[
			'Raul'		=> [
				'status'	=> 'dead',
				'job'		=> 'pilot',
				'expenses'	=> 200,
			],
			'Cifuentes'	=> [
				'status'	=> 'paunchy',
				'job'		=> 'engineer',
				'expenses'	=> 300,
			],
		]];
	}

	/**
	 * @dataProvider contacts
	 */
	public function testCallbackIsAppliedToRenderedJson($contacts) {
		// Wormold takes a pay rise
		$contacts['Raul']['expenses']		= 250;
		$contacts['Cifuentes']['expenses']	= 350;
		$payrise = function($contacts) {
			return array_map(function($contact) {
				$contact['expenses'] = $contact['expenses'] + 50;
				return $contact;
			}, $contacts);
		};
		$this->assertEquals(
			json_encode($contacts),
			$this->fixture->method(
				'json',
				$payrise
			)->render($contacts)
		);
	}
}