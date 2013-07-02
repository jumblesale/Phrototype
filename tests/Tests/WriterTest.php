<?php

namespace Phrototype\Tests;

use Phrototype\Writer;

class WriterTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->fixture = new Writer('tests/tmp');
		$this->baseDir = $this->fixture->getBaseDir();
	}

	public function __destruct() {
		$this->fixture->purge();
	}

	public function testPurge() {
		$files = ['test1', 'test2', 'test3'];
		foreach($files as $file) {
			touch($this->baseDir . $file);
			$this->assertFileExists($this->baseDir . $file);
		}
		$this->fixture->purge();
		foreach($files as $file) {
			$this->assertFileNotExists($this->baseDir . $file);
		}
	}

	public function testWriteToNewFile() {
		$this->fixture->write('test', 'some test data');
		$this->assertFileExists(
			$this->baseDir . 'test'
		);
	}

	/**
	 * @depends testWriteToNewFile
	 */
	public function testRead() {
		$contents = $this->fixture->read('test');

		$this->assertEquals(
			'some test data',
			$contents
		);
	}
	
	/**
	 * @depends testWriteToNewFile
	 */
	public function testAppend() {
		$this->fixture->write('test', PHP_EOL . 'more test data');
		$this->assertEquals(
			'some test data' . PHP_EOL . 'more test data',
			$this->fixture->read('test')
		);
	}
}