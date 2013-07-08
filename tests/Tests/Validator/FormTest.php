<?php

namespace Phrototype\Tests;

use Phrototype\Validator\Field;
use Phrototype\Validator\Form;

class FormTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->fixture = new Form();
	}

	public function testResolveType() {
		$blankField = new Field('empty');
		$this->assertEquals(
			'input',
			$this->fixture->resolveType($blankField)
		);

		$password = new Field('pwd', 'password');
		$this->assertEquals(
			'password',
			$this->fixture->resolveType($password)
		);

		$implicitPassword = new Field('password');
		$this->assertEquals(
			'password',
			$this->fixture->resolveType($password)
		);

		$select = new Field('pickanumber');
		$select->options(['1' => 'one', '2' => 'two']);
		$this->assertEquals(
			'select',
			$this->fixture->resolveType($select)
		);
	}

	public function testMethod() {
		$form = Form::create()->method('get');

		$this->assertEquals('get', $form->method());
		$form->method('post');
		$this->assertEquals('post', $form->method());
		$form->method('not a valid method');
		$this->assertEquals('not a valid method', $form->method());
	}

	public function testAttributes() {
		$form = new Form();
		$form->attributes([
				'class' => 'input-1 input-2',
				'disabled' => 'disabled',
				'clearlynotanattribute' => 'whatever',
			]);
		$this->assertEquals(
			[
				'class' => 'input-1 input-2',
				'disabled' => 'disabled',
				'clearlynotanattribute' => 'whatever',
			],
			$form->attributes()
		);
	}

	public function testAction() {
		$form = Form::create()->action('dosomething.aspx');
		$this->assertEquals('dosomething.aspx', $form->action());
	}
}