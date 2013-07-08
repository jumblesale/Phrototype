<?php

namespace Phrototype\Tests;

use Phrototype\Validator\Field;
use Phrototype\Validator\Form;

class FormHtmlTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->dom = new \DOMDocument();
	}

	public function testCreateInput() {
		$form = new Form();
		$input = Field::create('username');
		$this->dom->loadHTML($form->html($input));
		$input = $this->dom->getElementsByTagName('input')->item(0);
		$this->assertNotNull($input);
		$this->assertEquals(
			'input',
			$input->nodeName
		);
		$this->assertEquals(
			'username',
			$input->getAttribute('name')
		);
	}

	public function testCreateSelect() {
		$form = new Form();
		$select = Field::create('ducks')
			->options(explode(' ', 'mandarin tufted runner'));
		$html = $form->html($select);
		$this->dom->loadHTML($html);
		$select = $this->dom->getElementsByTagName('select')->item(0);
		$this->assertNotNull($select);
		$this->assertEquals(
			'select',
			$select->nodeName
		);
		$this->assertEquals(
			3,
			$select->getElementsByTagName('option')->length
		);
		foreach($select->childNodes as $option) {
			$this->assertEquals(
				'option',
				$option->nodeName
			);
		}
	}

	public function testCanCreateContainers() {
		$form = new Form();
		$input = Field::create('username')
			->container('div', ['class' => 'input-1']);
		$this->dom->loadHTML($form->html($input));
		$div = $this->dom->getElementsByTagName('div')->item(0);
		$this->assertNotNull($div);
		$this->assertEquals(
			'input-1',
			$div->getAttribute('class')
		);
		$input = $div->getElementsByTagName('input')->item(0);
		$this->assertNotNull($input);
	}

	public function testCanCreateForm() {
		$form = Form::create([
			Field::create('username'),
			Field::create('password'),
			Field::create('favourite duck')
				->options(explode(' ', 'mandarin tufted runner'))
		])
			->method('post')
			->action('/submit.pl');

		$html = $form->html();
		$this->dom->loadHTML($html);
		$form = $this->dom->getElementsByTagName('form')->item(0);
		$this->assertNotNull($form);
		$this->assertEquals(
			'post',
			$form->getAttribute('method')
		);
		$this->assertEquals(
			'/submit.pl',
			$form->getAttribute('action')
		);

		$username = $form->getElementsByTagName('input')->item(0);
		$password = $form->getElementsByTagName('input')->item(1);
		$this->assertNotNull($username);
		$this->assertNotNull($password);
		$duck = $form->getElementsByTagName('select')->item(0);
		$this->assertNotNull($duck);
	}
}