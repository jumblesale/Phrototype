<?php

namespace Phrototype\Tests\Model;

use Phrototype\Writer;
use Phrototype\Utils;
use Phrototype\Model;

class AddTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->array = [
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
		];
	}

	public function philosophers() {
		$models = Model\Factory::load($this->array);

		return $models;
	}

	public function tearDown() {
		$w = new Writer('tests/tmp');
		$w->purge();
	}

	public function testAddingANewElement() {
		$models = $this->philosophers();

		$hobbes = Model::forge([
			'id' => 11,
			'name' => 'hobbes',
			'school' => 'empiricism'
		]);

		$models->add($hobbes);

		$this->assertEquals(
			$hobbes,
			$models[11]
		);
	}

	public function testAddingNewElementWithoutId() {
		$models = $this->array;
		foreach($models as $i => $model) {
			$models[$i]['id'] = null;
		}
		$models = Model\Factory::load($models);

		$hobbes = Model::forge([
			'name' => 'hobbes',
			'school' => 'empiricism'
		]);

		$models->add($hobbes);

		$this->assertEquals(
			$hobbes,
			$models[3]
		);
	}

	public function testAddingAndSaving() {
		$array = [
			['name' => 'tufted'],
			['name' => 'mandarin'],
			['name' => 'runner']
		];
		$ducks = Model\Factory::load($array);

		$ducks->add(Model::forge(['name' => 'mallard']));

		$ducks->save('tests/tmp/ducks.json');

		$loaded = Model\Factory::load('tests/tmp/ducks.json');

		$this->assertEquals(
			Model\Factory::load([
				['name' => 'tufted'],
				['name' => 'mandarin'],
				['name' => 'runner'],
				['name' => 'mallard'],
			]),
			$loaded
		);
	}
}