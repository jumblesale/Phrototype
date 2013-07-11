<?php

namespace Phrototype\Tests;

use Phrototype\Writer;
use Phrototype\Utils;
use Phrototype\Model;

class SaveTest extends \PHPUnit_Framework_TestCase {
	public function tearDown() {
		$this->w = new Writer('tests/tmp');
		$this->w->purge();
	}

	public function testSaveCreatesFile() {
		$dog = Model\Factory::create(['name' => 'Charles', 'sound' => 'bark']);

		$this->assertFileNotExists(
			Utils::getDocumentRoot() . 'tests/tmp/charles.json'
		);

		$dog->save('tests/tmp/charles.json');

		$this->assertFileExists(
			Utils::getDocumentRoot() . 'tests/tmp/charles.json'
		);
	}

	public function testSaveAndLoadSingleModel() {
		$dog = Model\Factory::create(['name' => 'Charles', 'sound' => 'bark']);

		$dog->save(
			'tests/tmp/charles.json'
		);

		$loaded = Model::load(
			'tests/tmp/charles.json'
		);

		$this->assertEquals(
			$dog,
			$loaded
		);
	}
}