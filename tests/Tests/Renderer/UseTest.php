<?php

namespace Phrototype\Tests\Renderer;

use Phrototype\Renderer\LibIncluder;
use Phrototype\Renderer\Renderer;

class UseTest extends \PHPUnit_Framework_TestCase {
	public function testImportingCssLib() {
		$css = new LibIncluder();
		$link = $css->import('normalize');
		$this->assertEquals(
			'<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.10.3/build/cssnormalize/cssnormalize-min.css">',
			$link
		);
	}

	public function testImportingJsLib() {
		$css = new LibIncluder();
		$link = $css->import('jquery');
		$this->assertEquals(
			'<script language="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>',
			$link
		);
	}

	public function testLocalLib() {
		$css = new LibIncluder();
		$link = $css->import('my lib', '/css/css.css');
		$this->assertEquals(
			'<link rel="stylesheet" type="text/css" href="/css/css.css">',
			$link
		);
	}

	public function testMultipleLibs() {
		$css = new LibIncluder();
		$links = $css->import([
			'normalize',
			['css' => '/css/css.css']
		]);

		$this->assertEquals(
			preg_replace('/[\t\n]*/', '',
			'<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.10.3/build/cssnormalize/cssnormalize-min.css">
			<link rel="stylesheet" type="text/css" href="/css/css.css">'),
			preg_replace('/[\t\n]*/', '', $links)
		);
	}

	public function testInsertingIntoTemplate() {
		$renderer = new Renderer();

		$renderer->template(
			'<html>
				<head>
					{{{css}}}
					{{{js}}}
				</head>
				<body><h1 id="message">{{{content}}}</h1>
					{{{script}}}
				</body>
			</html>', [
				'css' => $renderer->importer()->import('normalize'),
				'js' => $renderer->importer()->import('jquery'),
				'script' => '<script language="text/javascript">
						$("#message").hide().slideDown("slow");
					</script>'
		]);

		$this->assertEquals(
			preg_replace('/[\t\n]*/', '', '<html>
				<head>
					<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.10.3/build/cssnormalize/cssnormalize-min.css">
					<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
				</head>
				<body><h1 id="message">weeeeeeeeee!</h1>
					<script type="text/javascript">
						$("#message").hide().slideDown("slow");
					</script>
				</body>
			</html>'
			),
			preg_replace(
				'/[\t\n]*/',
				'',
				$renderer->method('mustache')->render(
					'{{message}}', ['message' => 'weeeeeeeeee!']
				)
			)
		);
	}
}