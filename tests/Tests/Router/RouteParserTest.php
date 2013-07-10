<?php

namespace Phrototype\Tests\Router;

use Phrototype\Router\RouteParser;

class RouteParserTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->request = [
			'DOCUMENT_ROOT' => '/srv/Phrototype',
			'SERVER_PROTOCOL' => 'HTTP/1.1',
			'SERVER_NAME' => 'localhost',
			'SERVER_PORT' => '1066',
			'REQUEST_URI' => '/?one=1&two=2',
			'REQUEST_METHOD' => 'GET',
			'SCRIPT_NAME' => '/index.php',
			'SCRIPT_FILENAME' => '/srv/Phrototype/index.php',
			'PHP_SELF' => '/index.php',
			'QUERY_STRING' => 'one=1&two=2',
			'PATH_INFO' => '/path/to/this/page'
		];
	}

	public function testRequestVerb() {
		$request = $this->request;
		$parser = new RouteParser($request);
		$this->assertEquals(
			'get',
			$parser->verb()
		);
		$request['REQUEST_METHOD'] = 'POST';
		$parser->request($request);
		$this->assertEquals(
			'post',
			$parser->verb()
		);
	}

	public function testQuery() {
		$parser = new RouteParser($this->request);
		$this->assertEquals(
			['one' => 1, 'two' => 2],
			$parser->query()
		);
	}

	public function testPath() {
		$parser = new RouteParser($this->request);
		$this->assertEquals(
			'/path/to/this/page',
			$parser->path()
		);
	}
}