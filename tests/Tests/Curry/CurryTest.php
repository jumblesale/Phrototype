<?php

namespace Phrototype\Tests\CurryTest;

use Phrototype\Curry\Bind;

class Adder {
	function add($a, $b) {
		return implode(' ',
			[$a, '+', $b, '=', $a + $b]);
	}
}

class CurryTest extends \PHPUnit_Framework_TestCase {
	public function testCurryingAnonymousFunction() {
		$fn = function($a, $b) {
			return implode([$a, $b], ' ');
		};

		$this->assertEquals(
			'chicken phaal',
			$fn('chicken', 'phaal')
		);

		$lambCurry = Bind::curry($fn, 'lamb');

		$this->assertEquals(
			'lamb saag',
			$lambCurry('saag')
		);

		$this->assertEquals(
			'lamb rogan josh',
			$lambCurry('rogan josh', 'a miserable pile of parameters'),
			'Extra arguments are ignored'
		);
	}

	public function testCurryingAClassMethod() {
		$adder = new Adder();
		$add1 = Bind::curry('add', 1, $adder);

		$this->assertEquals(
			'1 + 2 = 3',
			$add1(2)
		);
	}
}