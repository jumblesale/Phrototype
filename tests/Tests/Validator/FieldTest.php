<?php

namespace Phrototype\Tests;

use Phrototype\Validator\Field;

class FieldTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->fixture = new Field();
	}

	public function testAddingConstraint() {
		$this->fixture->constrain('length', [0, 256]);
		$this->assertTrue(
			in_array('length', array_keys($this->fixture->constraints()))
		);
	}

	public function testCurryingConstraint() {
		$fn = $this->fixture->curryConstraint(
			'length', [0, 256]
		);

		$this->assertFalse($fn(257));
		$this->assertTrue($fn(3));
	}
}