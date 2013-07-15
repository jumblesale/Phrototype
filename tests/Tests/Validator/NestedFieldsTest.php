<?php

namespace Phrototype\Tests;

use Phrototype\Validator;

class FieldTest extends \PHPUnit_Framework_TestCase {
	public function testValidatingNestedFields() {
		$validator = new Validator();
		$validator->field('user[username]');
		$validator->field('user[password]')
			->constrain('length', [0, 8],
				'Password must be between 0 and 8 characters long')
			->constrain('regex', '/^w.+$/',
				'Password must start with w')
			->constrain('not', ['password', '12345'],
				'Pick a more imaginative password you plank');
		$validator->field('user[details][email]')
			->type('email');
		$validator->field('user[details][href]');
		$validator->field('feedback[comments]');

		$validData = [
			'user' => [
				'username' => 'charles',
				'password' => 'w00f!',
				'details' => [
					'email' => 'charles@barksmoore.dog',
					'href' => 'http://www.puggeshoppe.io'
				]
			],
			'feedback' => ['comments' => 'affaffaff']
		];

		$this->assertTrue($validator->validate($validData));
	}
}