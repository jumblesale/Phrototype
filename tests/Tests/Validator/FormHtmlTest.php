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

	public function testGroups() {
		$form = Form::create();

		$fields = [
			'Login details' => [
				Field::create('username'),
				Field::create('password'),
			],
			'Your details' => [
				Field::create('name'),
				Field::create('email'),
			]
		];

		$html = $form->html($fields);
		$this->dom->loadHTML($html);
		$login = $this->dom->getElementsByTagName('fieldset')->item(0);
		$this->assertNotNull($login);
		$this->assertEquals(
			'Login details',
			$login->getElementsByTagName('legend')
				->item(0)->nodeValue
		);
		$this->assertEquals(
			'username',
			$login->getElementsByTagName('input')
				->item(0)->getAttribute('name')
		);
		$this->assertEquals(
			'password',
			$login->getElementsByTagName('input')
				->item(1)->getAttribute('name')
		);
		$details = $this->dom->getElementsByTagName('fieldset')->item(1);
		$this->assertNotNull($details);
		$this->assertEquals(
			'Your details',
			$details->getElementsByTagName('legend')
				->item(0)->nodeValue
		);
		$this->assertEquals(
			'name',
			$details->getElementsByTagName('input')
				->item(0)->getAttribute('name')
		);
		$this->assertEquals(
			'email',
			$details->getElementsByTagName('input')
				->item(1)->getAttribute('name')
		);
	}

	public function testLabels() {
		$form = Form::create([
			Field::create('username')->description('Username:'),
		])
			->method('post')
			->action('/submit.pl');

		$this->dom->loadHTML($form->html());

		$label = $this->dom->getElementsByTagName('label')->item(0);
		$this->assertNotNull($label);
		$this->assertEquals(
			'Username:',
			$label->nodeValue
		);
		$this->assertEquals(
			'username',
			$label->getAttribute('for')
		);
	}

	public function testSubmit() {
		$form = Form::create([
			Field::create('username')->description('Username:'),
		]);

		$this->assertNull($form->submit());

		$this->dom->loadHTML($form->html());

		$submit = $this->dom->getElementsByTagName('input')->item(1);
		$this->assertNotNull($submit);
		$this->assertEquals(
			'submit',
			$submit->getAttribute('type')
		);
		$this->assertEquals(
			'Submit',
			$submit->getAttribute('value')
		);

		$form = Form::create([
			Field::create('username')->description('Username:'),
		])->submit('send it hence');
		
		$this->dom->loadHTML($form->html());

		$submit = $this->dom->getElementsByTagName('input')->item(1);
		$this->assertEquals(
			'send it hence',
			$submit->getAttribute('value')
		);
	}
}