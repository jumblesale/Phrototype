<?php

namespace Phrototype\Tests;

use Phrototype\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->fixture = new Validator();
	}

	public function testInterface() {
		$validator->field('username')->group('login');
		$validator->field('password')
				->constrain('length', [0, 256],
					'Password must be between 0 and 256 characters long')
				->constrain('regex', '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])$/',
					'Password must contain lowercase, uppercase and numeric characters')
				->constrain('not', ['password', '12345'],
					'Pick a more imaginative password you plank')
				->group('login');
		$validator->field('confirm')->group('login')
			->constrain('matches', 'password');
	}
}