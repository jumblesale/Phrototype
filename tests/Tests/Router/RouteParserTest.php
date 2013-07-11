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
			'REQUEST_URI' => '/something/someotherthing?one=1&two=2',
			'REQUEST_METHOD' => 'GET',
			'SCRIPT_NAME' => '/index.php',
			'SCRIPT_FILENAME' => '/srv/Phrototype/index.php',
			'PHP_SELF' => '/something/someotherthing',
			'QUERY_STRING' => 'one=1&two=2',
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

	public function testGetQuery() {
		$parser = new RouteParser($this->request);
		// bleck
		$_GET['one'] = 1;
		$_GET['two'] = 2;
		$this->assertEquals(
			['one' => 1, 'two' => 2],
			$parser->query()
		);
	}

	public function testPostQuery() {
		$request = $this->request;
		$request['REQUEST_METHOD'] = 'POST';
		$parser = new RouteParser($request);
		// bleck
		$_POST['pre'] = 'post';
		$_POST['post'] = 'mail';
		$this->assertEquals(
			['pre' => 'post', 'post' => 'mail'],
			$parser->query()
		);
	}

	public function testPath() {
		$parser = new RouteParser($this->request);
		$this->assertEquals(
			'/something/someotherthing',
			$parser->path()
		);
	}
}