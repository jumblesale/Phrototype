<?php

namespace Phrototype\Tests\Renderer;

use Phrototype\Renderer;

class TemplateTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->renderer = new Renderer();
	}

	public function template() {
		return preg_replace(
			'/[\t\n]*/', '',
			'<html>
				<head><title>{{title}}</title></head>
				<body>
					<h1>{{heading}}!!</h1>
					<div>{{{content}}}</div>
				</body>
			</html>');
	}

	public function testSetTemplate() {
		$this->assertNull($this->renderer->getTemplate());

		$this->renderer->template($this->template());

		$this->assertEquals(
			$this->template(),
			$this->renderer->getTemplate()
		);
	}

	public function testRenderTemplate() {
		$r = $this->renderer->method('mustache')->template(
			$this->template(),
			['title' => 'pug warehouse'
			,'heading' => 'welcome to pug warehouse']
		)->render(
			'<p>{{greeting}}</p>',
			['greeting' => 'oh, hello']
		);
		$this->assertEquals(
			preg_replace(
				'/[\n\t]*/', '',
				'<html><head><title>pug warehouse</title></head>
					<body>
						<h1>welcome to pug warehouse!!</h1>
						<div><p>oh, hello</p></div>
					</body>
				</html>'),
			$r
		);
	}

	public function testChangingContentKey() {
		$template = preg_replace(
			'/[\t\n]*/', '',
			'<h1>{{content}}</h1>
			<div>{{{body}}}</div>'
		);

		$r = $this->renderer->method('mustache')
			->contentKey('body')
			->template($template, ['content' => 'olde time pug dog'])
			->render('<p>a pugge dogge of qualitie and excellence</p>');

		$this->assertEquals(
			preg_replace(
				'/[\t\n]*/', '',
				'<h1>olde time pug dog</h1>
				<div>
					<p>a pugge dogge of qualitie and excellence</p>
				</div>'
			),
			$r
		);
	}

	public function testChangingTemplateProperties() {
		$template = '<h1>{{title}}</h1><p>{{description}}</p>';

		$r = $this->renderer->method('mustache')
			->template($template,
				['title' => 'pugge shoppe'
				,'description' => 'buye a pugge']);
		$this->assertEquals(
			'<h1>pugge shoppe</h1><p>buye a pugge</p>',
			$r->render('')
		);
		$r->template()->title = 'pug shop';
		$r->template()->description = 'buy a pug';
		$this->assertEquals('<h1>pug shop</h1><p>buy a pug</p>', $r->render(''));
	}
}