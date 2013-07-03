<?php

namespace Phrototype\Tests;

use Phrototype\Curry\Bind;

class PartialTest extends \PHPUnit_Framework_TestCase {
	public function testAnonymousFunctionPartial() {
		$band = function($v, $g, $b, $d) {
			return implode("\n", [
				"vocals: $v",
				"guitar: $g",
				"bass: $b",
				"drums: $d"
			]);
		};

		$smiths = Bind::partial($band, Bind::…(), Bind::…(), 'Rourke', 'Joyce');

		$this->assertEquals(
			implode("\n", [
				"vocals: Morrissey",
				"guitar: Marr",
				"bass: Rourke",
				"drums: Joyce"
			]),
			$smiths('Morrissey', 'Marr')
		);

		// Morrissey we've had enough of your songs about vegetarianism
		$this->assertEquals(
			implode("\n", [
				"vocals: ",
				"guitar: ",
				"bass: Rourke",
				"drums: Joyce"
			]),
			$smiths('', '')
		);
	}

	public function testObjectPartial() {
		$adder = new Adder();
		$cube = Bind::partial([$adder, 'raise'], Bind::…(), 3);
		$this->assertEquals(
			8,
			$cube(2)
		);
	}
}

class Adder {
	public function raise($a, $b) {
		return pow($a, $b);
	}
}