<?php

namespace Phrototype\Tests;

use Phrototype\Writer;
use Phrototype\Utils;
use Phrototype\Model;

class SaveTest extends \PHPUnit_Framework_TestCase {
	public function __destruct() {
		$w = new Writer('tests/tmp');
		$w->purge();
	}

	public function testSaveCreatesFile() {
		$dog = Model\Factory::create(['name' => 'Charles', 'sound' => 'bark']);

		$this->assertFileNotExists('charles.json');

		$dog->save('charles', 'tests/tmp');

		$this->assertFileExists(
			Utils::getDocumentRoot() . 'tests/tmp/charles'
		);

		return $dog;
	}

	/**
	 * @depends testSaveCreatesFile
	 */
	public function testLoadReadsFiles($dog) {
		$read = $dog->load('tests/tmp/charles');
		$this->assertNotNull($read);
	}
}