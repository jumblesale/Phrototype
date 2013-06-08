<?php

namespace Phrototype\Tests\Prototype;

use Phrototype\Prototype;

class PrototypalInheritanceTest extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->animal = Prototype::create(null, 'Animal');

		$this->cat = Prototype::create($this->animal, 'Cat', [
			'name' => 'Chairman Meow', 'sound' => 'puurrRRRrrRRRrr',
		]);

		$this->dog = Prototype::create($this->animal, 'Dog', [
			'name' => 'Colonel Charles Barksmoore', 'sound' => 'woof!',
		]);
	}

	public function testPrototypeIsCorrectlySet() {
		$this->assertEquals(
			$this->animal,
			$this->dog->getPrototype()
		);
	}

	public function testAlteringInheritedObjectDoesNotChangePrototype() {
		$this->cat->says = function($self) {
			return $self->sound;
		};

		$this->assertEquals(
			$this->cat->sound,
			$this->cat->says()
		);

		$dogSays = $this->dog->says;

		$this->assertNull(
			$dogSays
		);
	}

	public function testAlteringPrototypeIsReflectedInInheritedObjects() {
		$this->animal->says = function($self) {
			return $this->sound;
		};

		$this->assertEquals(
			$this->cat->sound,
			$this->cat->says()
		);

		$this->assertEquals(
			$this->dog->sound,
			$this->dog->says()
		);
	}
}