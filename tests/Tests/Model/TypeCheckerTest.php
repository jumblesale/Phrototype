<?php

namespace Phrototype\Tests\Model;

use Phrototype\Model\TypeChecker;

class TypeCheckerTest extends \PHPUnit_Framework_TestCase {
	public function integers() {
		return [[1, true], ['one', false], ['1', true], [1.1, true]];
	}

	/**
	 * @dataProvider integers
	 */
	public function testInteger($v, $t) {
		$this->assertEquals($t, TypeChecker::check('integer', $v));
	}

	public function dates() {
		return [['2013-01-01', true]];
		// return [['0000-00-00', false]];
	}

	/**
	 * @dataProvider dates
	 */
	public function testDate($v, $t) {
		$this->assertEquals($t, TypeChecker::check('date', $v));
	}
}