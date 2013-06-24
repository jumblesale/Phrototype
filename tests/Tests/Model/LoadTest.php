<?php

namespace Phrototype\Tests;

use Phrototype\Model;
use Phrototype\Prototype;

class LoadTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->book = ['title' => null, 'author' => null, 'price' => null];
		$this->bookshelf = [
			// Some dystopias in descending order of quality
			['title' => '1984', 'author' => 'Orwell', 'price' => '3'],
			['title' => 'Brave New World', 'author' => 'Huxley', 'price' => '2.5'],
			['title' => 'Fahrenheit 451', 'author' => 'Bradbury',
				// This one was clearly found in a jumblesale
				'price' => '0.5']
		];
	}


	public function testLoadingProducesObjectWithFieldsSetCorrectly() {
		$book		= Model\Factory::create($this->book);
		$bookshelf	= $this->bookshelf;
		$dystopias	= Model\Factory::load($bookshelf, $book);

		$this->assertTrue(is_array($dystopias));
		$this->assertFalse(empty($dystopias));

		foreach($dystopias as $i => $dystopia) {
			$this->assertInstanceOf('\Phrototype\Prototype', $book);
			foreach($bookshelf[$i] as $property => $value) {
				$this->assertEquals(
					$value,
					$dystopia->getProperties()[$property]
				);
			}
		}
	}

	public function testLoadingOnPrototypeSetsPrototypeInReturnedObjects() {
		$proto		= Model\Factory::create($this->book);
		$bookshelf	= $proto->load($this->bookshelf);

		$this->assertNotEmpty($bookshelf);
		$this->assertEquals(3, sizeof($bookshelf));

		foreach($bookshelf as $i => $book) {
			$this->assertContains($this->bookshelf[$i], $book->getProperties());
		}
	}
}