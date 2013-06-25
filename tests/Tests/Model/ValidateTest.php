<?php

namespace Phrototype\Tests;

use Phrototype\Model;

class ValidateTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->user = [
			'username' => [
				'type' 			=> 'text',
				'constraints'	=> [
					'length'	=> [3, 32],
				],
			],
			'dob' => [
				'type'			=> 'date',
			],
			'password' => [
				
			],
		];
	}

	public function testConstrainingANewFieldCreatesThisField() {
		$user = Model\Factory::create($this->user);

		$user->constrain('age', ['type' => 'integer', 'range' => [1, 256]]);

		$this->assertContains(
			'age',
			array_keys($user->fields())
		);
	}

	public function testCanAddConstraintsToAField() {
		return true;
		$user = Model\Factory::create($this->user);

		$user->constrain('');
	}
}