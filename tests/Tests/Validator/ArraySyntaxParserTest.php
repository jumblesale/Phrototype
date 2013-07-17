<?php

namespace Phrototype\Tests;

use Phrototype\Validator\ArraySyntaxParser;

class ArraySyntaxParserTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->parser = new ArraySyntaxParser();
	}

	public function testErrantKey() {
		$this->assertNull(
			$this->parser->parse(
				[],
				'depth-1'
			)->value()
		);
	}

	public function testSingleLevelArray() {
		$this->assertEquals(
			3,
			$this->parser->parse(
				['depth-1' => 3],
				'depth-1'
			)->value()
		);
	}

	public function testSingleDepth() {
		$this->assertEquals(
			5,
			$this->parser->parse(
				['sea' => ['monster' => 5]],
				'sea[monster]'
			)->value()
		);
	}

	public function testComplexData() {
		$this->assertEquals(
			11,
			$this->parser->parse(
				['sea' => [
					'monster' => 5,
					'horse' => [
						'shoe' => 12,
					],
					'pony' => [
						'trek' => [
							'star' => 11
						]
					]
				]],
				'sea[pony][trek][star]'
			)->value()
		);
	}

	public function testToArray() {
		$this->parser->parse(
			['sea' => [
				'monster' => 5,
				'horse' => [
					'shoe' => 12,
				],
				'pony' => [
					'trek' => [
						'star' => 11
					]
				]
			]],
			'sea[pony][trek][star]'
		)->toArray();
	}
}