<?php

namespace Phrototype\Tests;

use Phrototype\Model;
use Phrototype\Prototype;

class CreateTest extends \PHPUnit_Framework_TestCase {
	public function testCreateReturnsANewPrototype() {
		$o = Model\Factory::create([]);

		$this->assertInstanceOf('\Phrototype\Prototype', $o);
	}

	public function assertCreateWithFieldsReturnsAPrototypeWithThoseFields() {
		// We are starting a book shop ok
		$book = Model\Factory::create([
			'title' => null, 'synopsis' => null
		]);

		$properties = $book->getProperties;

		foreach($properties as $name => $property) {
			$this->assertTrue(in_array($name), array_keys($book));
		}
	}

	public function testCanCreateWithoutSpecifyingAdditionalDetails() {
		$properties = ['title' => 'Don Quixote', 'author' => 'Cervantes'];
		$book = Model\Factory::create($properties);

		foreach($properties as $name => $property) {
			$this->assertEquals(
				$properties[$name],
				$book->getProperties()[$name]
			);
		}
	}

	public function testCanCreateFromExistingObject() {
		$product = Model\Factory::create([
			'id' => '1', 'price' => '3',
		]);

		$book = Model\Factory::create(
			['id' => '2', 'title' => 'Don Quixote'],
			$product
		);

		$properties = ['id' => '2', 'price' => '3', 'title' => 'Don Quixote'];

		foreach($properties as $name => $property) {
			$this->assertEquals(
				$properties[$name],
				$book->getProperties()[$name]
			);
		}
	}

	public function testCanCreateNestedDataTypes() {
		$author = Model\Factory::create([
			'name' => null, 'nationality' => null,
		]);
		$book = Model\Factory::create([
			'title' => null,
			'author' => $author,
		]);

		$this->assertEquals(
			['title' => null,
			 'author' => $author],
			$book->getProperties()
		);
	}
}