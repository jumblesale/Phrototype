<?php

namespace Phrototype\Tests;

use Phrototype\Validator\Field;
use Phrototype\Validator\FormBuilder;

class FormTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->fixture = new FormBuilder();
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
}