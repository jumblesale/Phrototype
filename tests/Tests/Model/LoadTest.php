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

	public function testCanForgeASingleElement() {
		$author	= Model\Factory::create([
			'name' => null,	'nationality' => null,
		]);

		$kafka = $author->forge([
			'name' => 'Kafka', 'nationality' => 'czech',
		]);

		$this->assertEquals(
			['name' => 'Kafka', 'nationality' => 'czech'],
			$kafka->getProperties()
		);

		return $kafka;
	}

	/**
	 * @depends testCanForgeASingleElement
	 */
	public function testCanLoadWithNestedObjects($kafka) {
		$book = $this->book;
		$book['author'] = $kafka;

		$model = Model\Factory::create($book);

		$author = $model->author;
		
		$this->assertEquals($author, $kafka);
	}

	public function testCanLoadFromLocation() {
		$json = json_encode($this->bookshelf);

		$writer = new \Phrototype\Writer('tests/tmp');

		// Empty the file
		$writer->write('books.json', '');
		$writer->write('books.json', $json);

		$loadedBooks = Model\Factory::load('tests/tmp/books.json');

		$writer->purge();

		$this->assertEquals(
			Model\Factory::load($this->bookshelf),
			$loadedBooks
		);
	}
}