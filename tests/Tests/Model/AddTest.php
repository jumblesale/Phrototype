<?php

namespace Phrototype\Tests\Model;

use Phrototype\Writer;
use Phrototype\Utils;
use Phrototype\Model;

class AddTest extends \PHPUnit_Framework_TestCase {
	public function __destruct() {
		$w = new Writer('tests/tmp');
		$w->purge();
	}

	public function testShutupPhpunit() {
		$this->assertTrue(true);
	}
}