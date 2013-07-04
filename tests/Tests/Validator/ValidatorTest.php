<?php

namespace Phrototype\Tests;

use Phrototype\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->fixture = new Validator();
	}

	public function testAddingFields() {
		$this->fixture->field('username');
		$this->fixture->field('password');

		$this->assertEquals(
			['username', 'password'],
			array_keys($this->fixture->fields())
		);
	}

	public function testGroups() {
		$this->fixture->group('login')->field('username');
		$this->fixture->group('login')->field('password');
		$this->fixture->group('details')->field('name');
		$this->fixture->group('details')->field('email');

		$login = $this->fixture->groups()['login'];
		$details = $this->fixture->groups()['details'];

		$this->assertEquals(
			['username', 'password'],
			array_values($login)
		);
		$this->assertEquals(
			['name', 'email'],
			array_values($details)
		);
	}

	public function testValidating() {
		$validator = $this->fixture;
		$validator->group('login')->field('username');
		$validator->group('login')->field('password')
				->constrain('length', [0, 8],
					'Password must be between 0 and 8 characters long')
				->constrain('regex', '/^w.+$/',
					'Password must start with w')
				->constrain('not', ['password', '123456789'],
					'Pick a more imaginative password you plank');

		$this->assertTrue($validator->validate(
			['username' => 'charles', 'password' => 'w0oF']
		));
		$this->assertFalse($validator->validate(
			['username' => 'charles', 'password' => 'w0oFfffff']
		));
		$this->assertEmpty($validator->messages('username'));
		$this->assertEquals(
			'Password must be between 0 and 8 characters long',
			$validator->messages('password')[0]
		);
		$this->assertFalse($validator->validate(
			['username' => 'charles', 'password' => '123456789']
		));
		$this->assertEquals(
			[
				'Password must be between 0 and 8 characters long',
				'Password must start with w',
				'Pick a more imaginative password you plank',
			],
			$validator->messages('password')
		);
	}
}