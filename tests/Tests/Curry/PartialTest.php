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
}