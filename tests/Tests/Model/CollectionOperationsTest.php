<?php

namespace Phrototype\Tests\Model;

use Phrototype\Model;

class CollectionsOperationsTest extends \PHPUnit_Framework_TestCase {
	public function countries() {
		$countries = Model\Factory::load([
			[
				'name' => 'russia',
				'area' => 16377742,
				'pop' => 143000000,
				'size' => 'massive',
			],
			[
				'name' => 'vatican city',
				'area' => 0.44,
				'pop' => 800,
				'size' => 'tiny',
			],
			[
				'name' => 'monaco',
				'area' => 2.02,
				'pop' => 36371,
				'size' => 'tiny'
			],
		]);

		return $countries;
	}

	public function countriesWithIds() {
		$countries = $this->countries();
		$countries[0]->id = 2;
		$countries[1]->id = 6;
		$countries[2]->id = 3;
		return $countries;
	}

	public function testReverse() {
		$countries = $this->countries()->reverse();
		$this->assertEquals($this->countries()[2], $countries[0]);
		$this->assertEquals($this->countries()[1], $countries[1]);
		$this->assertEquals($this->countries()[0], $countries[2]);
	}

	public function testFind() {
		$countries = $this->countries();

		$tinyCountries = $countries->find('size', 'tiny');

		$this->assertEquals(
			$this->countries()[1],
			$tinyCountries[0]
		);

		$this->assertEquals(
			$this->countries()[2],
			$tinyCountries[1]
		);
	}

	public function testFindWithIds() {
		$countries = $this->countriesWithIds();

		$tinyCountries = $countries->find('size', 'tiny');

		$this->assertEquals(
			$this->countriesWithIds()[1],
			$tinyCountries[6]
		);

		$this->assertEquals(
			$this->countriesWithIds()[2],
			$tinyCountries[3]
		);
	}

	public function testOrderByNumeric() {
		$countries = $this->countries()->order('area', false);
		$expected = ['russia', 'monaco', 'vatican city'];
		for($i = 0; $i < count($countries); $i += 1) {
			$this->assertEquals(
				$expected[$i],
				$countries[$i]->name
			);
		}
	}
}