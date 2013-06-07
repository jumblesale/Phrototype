<?php

namespace Phrototype\Tests\Curry;

use Phrototype\Curry;

class CurryTest extends \PHPUnit_Framework_TestCase {
	public function testOneIsOne() {
		return $this->assertEquals(1, 1);
	}
}