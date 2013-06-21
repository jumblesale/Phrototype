<?php

namespace Phrototype\Tests;

use Phrototype\Model\Model;
use Phrototype\Prototype;

class CreateTest extends \PHPUnit_Framework_TestCase {
	public function testCreateReturnsANewPrototype() {
		$o = Model::create([]);

		$this->assertInstanceOf('\Phrototype\Prototype', $o);
	}

	public function assertCreateWithFieldsReturnsAPrototypeWithThoseFields() {
		// We are starting a book shop ok
		$book = Model::create([
			'title' => null, 'synopsis' => null
		]);

		$properties = $book->getProperties;

		foreach($properties as $name => $property) {
			$this->assertTrue(in_array($name), array_keys($book));
		}
	}

	public function testCanSetAPrototypeWithTypesAndNotHaveThemInTheObject() {
		// Start with a good book
		$book = Model::create([
			'title'	=> ['type' => 'string', 'value' => 'Don Quixote'],
			'published' =>['type' => 'date', 'value' => '1605-05-21'],
			'edition' => ['type' => 'integer', 'value' => '1']
		]);

		$properties = ['title' => 'Don Quixote', 'published' => '1605-05-21', 'edition' => '1'];

		foreach($properties as $name => $value) {
			$this->assertEquals(
				$properties[$name],
				$book->getProperties()[$name]
			);
		}
	}

	public function testCreatingWithWrongTypesDropsValues() {
		$book = Model::create([
			'title'	=> ['type' => 'string', 'value' => []],
			'edition' => ['type' => 'int', 'value' => 'one']
		]);

		$properties = ['title' => null, 'edition' => null];
			
		foreach($properties as $name => $value) {
			$this->assertEquals(
				$properties[$name],
				$book->getProperties()[$name]
			);
		}
	}

	public function testCanCreateWithoutSpecifyingAdditionalDetails() {
		$properties = ['title' => 'Don Quixote', 'author' => 'Cervantes'];
		$book = Model::create($properties);

		foreach($properties as $name => $property) {
			$this->assertEquals(
				$properties[$name],
				$book->getProperties()[$name]
			);
		}
	}

	public function testCanCreateFromExistingObject() {
		$product = Model::create([
			'id' => '1', 'price' => '3',
		]);

		$book = Model::create(
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
}