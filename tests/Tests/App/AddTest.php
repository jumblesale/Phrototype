<?php

namespace Phrototype\Tests;

use Phrototype;
use Phrototype\Model;
use Phrototype\Validator;

class AddTest extends \PHPUnit_Framework_TestCase {
	public function testAdd() {
		$app = new \Phrototype\App();

		$validator = Validator::create();
		$validator->group('post', 'Post')->field('title')
			->description('Post title:');
		$validator->group('post')->field('content')->type('text')
			->description('Post content:');
		$validator->group('author', 'Author details')->field('name')
			->description('Author name:');
		$validator->group('author')->field('email')
			->description('Author email:')
			->attributes(['placeholder' => 'name@domain.com']);

		$this->assertTrue(true);
	}
}