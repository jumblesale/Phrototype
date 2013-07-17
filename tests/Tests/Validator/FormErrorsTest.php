<?php

namespace Phrototype\Tests;

use Phrototype\Validator\Field;
use Phrototype\Validator\Form;

class FormErrorsTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->form = new Form();
		$this->dom = new \DOMDocument();
	}

	public function testErrors() {
		$this->form->errors([
			'field' => ['message'],
			'field2' => ['message2'],
		]);

		$this->assertEquals(
			['field' => ['message'],'field2' => ['message2']],
			$this->form->errors()
		);
	}

	public function testDisplayingErrors() {
		$this->form->fields([
			new Field('username'),
			new Field('password')
		]);
		$this->form->errors([
			'username' => ['incorrect username', 'that username is stupid'],
			'password' => ['incorrect password']
		]);

		$html = $this->form->html();
		$this->dom->loadHTML($html);

		$usernameError = $this->dom->getElementsByTagName('div')->item(0);
		$passwordError = $this->dom->getElementsByTagName('div')->item(1);

		$this->assertNotNull($usernameError);
		$this->assertNotNull($passwordError);

		$this->assertTrue(
			strpos($usernameError->nodeValue, 'incorrect username') !== false
		);
		$this->assertTrue(
			strpos($usernameError->nodeValue, 'that username is stupid')
				!== false
		);

		$this->assertTrue(
			strpos($passwordError->nodeValue, 'incorrect password') !== false
		);
	}
}