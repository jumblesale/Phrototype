<?php

namespace Phrototype\Tests\Validator;

use Phrototype\Validator\Field;

class FieldTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->fixture = new Field();
	}

	public function testAddingConstraint() {
		$this->fixture->constrain('length', [0, 8]);
		$this->assertTrue(
			in_array('length', array_keys($this->fixture->constraints()))
		);
	}

	public function attributesTest() {
		$field = new Field('username');
		$field->attributes([
				'class' => 'input-1 input-2',
				'disabled' => 'disabled',
				'clearlynotanattribute' => 'whatever',
			]);
		$this->assertEquals(
			[
				'class' => 'input-1 input-2',
				'disabled' => 'disabled',
				'clearlynotanattribute' => 'whatever',
			]
		);
	}

	public function testCurryingConstraint() {
		$fn = $this->fixture->curryConstraint(
			'length', [0, 8]
		);

		$this->assertFalse($fn('123456789'));
		$this->assertTrue($fn('123'));
	}

	public function testAddingAndInvokingConstraint() {
		$this->fixture->constrain('length', [0, 8]);
		$this->assertTrue(
			$this->fixture->test('length', '123')
		);
		$this->assertFalse(
			$this->fixture->test('length', '123456789')
		);
	}

	public function testMultipleConstraints() {
		$this->fixture->constrain('length', [1, 3])
			->constrain('range', [1, 256]);

		$this->assertTrue($this->fixture->validate(12));
		$this->assertTrue($this->fixture->validate(123));
		$this->assertFalse($this->fixture->validate(1234));
		$this->assertFalse($this->fixture->validate(257));
	}

	public function testMessagesAreGenerated() {
		$this->fixture
			->constrain('length', [1, 3], 'length failed')
			->constrain('range', [1, 256], 'range failed');

		$this->fixture->validate(12345);

		$this->assertEquals(
			['length failed', 'range failed'],
			$this->fixture->messages()
		);
	}

	public function testRequired() {
		$this->fixture->required(false);
		$this->assertFalse($this->fixture->required());
	}

	public function testOptions() {
		$this->fixture->options(['some' => 'things', 'are' => 'here']);
		$this->assertEquals(
			['some' => 'things', 'are' => 'here'],
			$this->fixture->options()
		);
	}
}