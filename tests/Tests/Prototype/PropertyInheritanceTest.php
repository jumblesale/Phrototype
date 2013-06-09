<?php

namespace Phrototype\Tests\Prototype;

use Phrototype\Prototype;

class PropertyInheritanceTest extends \PHPUnit_Framework_TestCase {
	public function testPropertiesAreInherited() {
		$prototype = Prototype::create();

		$prototype->name = 'Kierkegaard';

		$this->assertEquals(
			'Kierkegaard',
			$prototype->name,
			'What does it mean to have a property exist?'
		);

		$inheritor = Prototype::create($prototype);

		$this->assertEquals(
			'Kierkegaard',
			$inheritor->name,
			'Cloning dead philosophers works'
		);

		$inheritor->name = 'Camus';

		$this->assertEquals(
			'Camus',
			$inheritor->name
		);

		$this->assertEquals(
			'Kierkegaard',
			$prototype->name
		);
	}

	public function testMethodsAreInherited() {
		$add = function($self, $a, $b) {
			return $a + $b;
		};

		$proto = Prototype::create();

		$proto->add = $add;

		$this->assertTrue(
			is_callable([$proto, 'add']),
			'Prototype gets an add method'
		);

		$this->assertEquals(
			3,
			$proto->add(1, 2),
			'Prototype\'s add method can be invoked'
		);

		$inheritor = Prototype::create($proto);

		$this->assertTrue(
			is_callable([$inheritor, 'add']),
			'Add method is inherited'
		);

		$this->assertEquals(
			7,
			$inheritor->add(3, 4),
			'Inheritor\'s add method can be invoked'
		);
	}
}