<?php

namespace Phrototype\Tests;

use Phrototype\Utils;

class UtilsTest extends \PHPUnit_Framework_TestCase {
	public function testIsHash() {
		$this->assertTrue(
			Utils::isHash(['this is' => 'a hash'])
		);
		$this->assertFalse(
			Utils::isHash(['this', 'is', 'not', 'a', 'hash'])
		);
		$this->assertFalse(
			Utils::isHash([0 => 'this', 1 => 'is', 2 => 'an', 3 => 'array'])
		);
	}

	public function testGetFilExtension() {
		$this->assertEquals(
			'json',
			Utils::getFileExtension('data.json')
		);
		$this->assertEquals(
			'json',
			Utils::getFileExtension('data.data.json')
		);
		$this->assertFalse(
			Utils::getFileExtension('data')
		);
	}
}