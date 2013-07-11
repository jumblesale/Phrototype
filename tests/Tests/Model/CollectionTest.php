<?php

namespace Phrototype\Tests;

use Phrototype\Writer;
use Phrototype\Utils;
use Phrototype\Model;

class CollectionTest extends \PHPUnit_Framework_TestCase {
	public function philosophers() {
		$models = Model\Factory::load([
			[
				'id' => 3,
				'name' => 'locke',
				'school' => 'empiricism',
			],
			[
				'id' => 8,
				'name' => 'kierkegaard',
				'school' => 'existentialism',
				'hobbies' => ['rowing', 'pressing flowers']
			],
			[
				'id' => 1,
				'name' => 'descartes',
				'school' => 'cartesianism',
				'quote' => 'cogito ergo sum',
			],
		]);

		return $models;
	}

	public function testLoadingProducesIterableCollection() {
		$models = $this->philosophers();

		$this->assertEquals(
			'kierkegaard',
			$models[8]->name
		);
	}

	public function testIdsArePopulated() {
		$models = $this->philosophers();
		$ids = $models->ids();
		$this->assertEquals(
			[3, 8, 1],
			array_keys($ids)
		);
	}

	public function testConsecutiveIdsAreAssigned() {
		$models = Model\Factory::load([
			['spaniel'], ['poodle'], ['spadoodle']
		]);

		$this->assertEquals(
			[0, 1, 2],
			array_keys($models->ids())
		);
	}

	public function testToArray() {
		$models = $this->philosophers();

		$this->assertEquals(
			[3, 8, 1],
			array_keys($models->toArray())
		);

		$this->assertEquals(
			'cogito ergo sum',
			$models->toArray()[1]->quote
		);
	}
}