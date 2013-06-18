<?php

namespace Phrototype\Tests;

use Phrototype\Logue;

class LogueTest extends \PHPUnit_Framework_TestCase {
	public function testLevelIsSetByDefault() {
		$this->assertNull(Logue::level());
	}

	public function testMEssageIsLoggedIfLevelIsSameAsLogLevel() {
		Logue::level(Logue::ROYBATTY);
		$this->assertTrue(
			Logue::log(
				'attack ships on fire off the shoulder of orion',
				Logue::ROYBATTY
			)
		);
	}

	public function testLogsWithLevelLessThanLogLevelAreIgnored() {
		Logue::level(Logue::WARN);
		$this->assertFalse(
			Logue::log(
				'c beams glitter in the dark near the tannhäuser gate',
				Logue::ROYBATTY
			)
		);
	}
}