<?php

namespace Phrototype\Tests;

use Phrototype\Validator\Constraints;

class FieldTest extends \PHPUnit_Framework_TestCase {
	public function lengthData() {
		return [
			  [[[3],	'12',	true]]
			, [[[3],	'123',	true]]
			, [[[0, 3],	'12',	true]]
			, [[[0, 3],	'123',	true]]
			, [[[3],	'1234',	false]]
			, [[[0, 3],	'1234',	false]]
			, [[[0, 3],	'1234',	false]]
			, [[[5, 3],	'12',	false]]
			, [[[3],	'',		false]]
		];
	}

	/**
	 * @dataProvider lengthData
	 */
	public function testLength($data) {
		$length = new Constraints\length();
		$this->assertEquals(
			$data[2],
			$length->test($data[0], $data[1])
		);
	}

	public function rangeData() {
		return [
			  [[[3],		2,		true]]
			, [[[3],		3,		true]]
			, [[[0, 3],		3,		true]]
			, [[[-8, 3],	0,		true]]
			, [[[3],		5,		false]]
			, [[[0, 3],		-5,		false]]
			, [[[5, 3],		2,		false]]
			, [[[0, 3],		null,	false]]
		];
	}

	/**
	 * @dataProvider rangeData
	 */
	public function testRange($data) {
		$range = new Constraints\range();
		$this->assertEquals(
			$data[2],
			$range->test($data[0], $data[1])
		);
	}

	public function testIn() {
		$in = new Constraints\in();

		$absurdists = explode(' ',
			'kafka camus kierkegaard heller vonnegut'
		);

		$this->assertTrue($in->test($absurdists, 'kafka'));
		$this->assertTrue($in->test($absurdists, 'heller'));
		// get out hansen you are a bad man
		$this->assertFalse($in->test($absurdists, 'hansen'));
	}

	public function testNot() {
		$not = new Constraints\not();

		$primes = [2, 3, 5, 7, 11, 13, 17, 19, 23, 29];

		$this->assertTrue($not->test($primes, 4));
		$this->assertTrue($not->test($primes, 18));
		$this->assertFalse($not->test($primes, 2));
		$this->assertFalse($not->test($primes, 29));
	}

	public function testRegEx() {
		$regex = new Constraints\regex();

		$exp = '/^[a-zA-Z]+$/';

		$this->assertTrue($regex->test($exp, 'r'));
		// unicode snowman? FOR ME?
		$this->assertFalse($regex->test($exp, '☃'));
	}

	public function testMatches() {
		$matches = new Constraints\matches();

		$one	= 'one';
		$one2	= 'one';
		$two	= 'two';

		$this->assertTrue($matches->test($one, $one2));
		$this->assertFalse($matches->test($one, $two));
	}
}
