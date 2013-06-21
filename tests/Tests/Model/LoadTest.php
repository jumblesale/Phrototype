<?php

namespace Phrototype\Tests;

use Phrototype\Model\Model;
use Phrototype\Prototype;

class LoadTest extends \PHPUnit_Framework_TestCase {
	public function testLoadingProducesObjectWithFieldsSetCorrectly() {
		$book = Model::create(['title' => null, 'author' => null, 'price' => null]);

		// Some dystopias in descending order of quality
		$bookshelf = [
			['title' => '1984', 'author' => 'Orwell', 'price' => '3'],
			['title' => 'Brave New World', 'author' => 'Huxley', 'price' => '2.5'],
			['title' => 'Fahrenheit 451', 'author' => 'Bradbury',
				// This one was clearly found in a jumblesale
				'price' => '0.5']
		];
		$dystopias = Model::load($bookshelf, $book);

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
}