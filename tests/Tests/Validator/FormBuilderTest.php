<?php

namespace Phrototype\Tests;

use Phrototype\Validator\Field;
use Phrototype\Validator\FormBuilder;

class FormBuilderTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->fixture = new FormBuilder();
	}

	public function testResolveType() {
		$blankField = new Field('empty');
		$this->assertEquals(
			'input',
			$this->fixture->resolveType($blankField)
		);

		$password = new Field('pwd', 'password');
		$this->assertEquals(
			'password',
			$this->fixture->resolveType($password)
		);

		$implicitPassword = new Field('password');
		$this->assertEquals(
			'password',
			$this->fixture->resolveType($password)
		);

		$select = new Field('pickanumber');
		$select->options(['1' => 'one', '2' => 'two']);
		$this->assertEquals(
			'select',
			$this->fixture->resolveType($select)
		);
	}

	public function testMethod() {
		$form = FormBuilder::create()->method('get');

		$this->assertEquals('get', $form->method());
		$form->method('post');
		$this->assertEquals('post', $form->method());
		$form->method('not a valid method');
		$this->assertEquals('not a valid method', $form->method());
	}

	public function testAttributes() {
		$form = new FormBuilder();
		$form->attributes([
				'class' => 'input-1 input-2',
				'disabled' => 'disabled',
				'clearlynotanattribute' => 'whatever',
			]);
		$this->assertEquals(
			[
				'class' => 'input-1 input-2',
				'disabled' => 'disabled',
				'clearlynotanattribute' => 'whatever',
			],
			$form->attributes()
		);
	}

	public function testAction() {
		$form = FormBuilder::create()->action('dosomething.aspx');
		$this->assertEquals('dosomething.aspx', $form->action());
	}

	public function testContainers() {
		$form = new FormBuilder();
		$this->assertEquals(
			[
				'tag' => 'div',
				'attributes' => [
					'class' => 'input-1',
					'color' => 'pink'
				],
				'children' => [
					[
						'tag' => 'input',
						'attributes' => [
							'type' => 'text',
							'name' => 'username'
						]
					]
				]
			],
			$form->buildElement(Field::create('username')->container(
				'div', ['class' => 'input-1', 'color' => 'pink']
			))
		);
	}

	public function testBuildSelectOptions() {
		$field = Field::create('select')->options(
			[1 => 'one', 2 => 'two', 3 => 'three']
		)->value(3);

		$form = new FormBuilder();

		$this->assertEquals(
			[
				[
					'tag' => 'option',
					'attributes' => [
						'value' => '1'
					],
					'children' => 'one'
				],
				[
					'tag' => 'option',
					'attributes' => [
						'value' => '2'
					],
					'children' => 'two'
				],
				[
					'tag' => 'option',
					'attributes' => [
						'value' => '3',
						'selected' => 'selected',
					],
					'children' => 'three'
				],
			],
			$form->buildSelectOptions($field->options(), $field->value())
		);
	}

	public function testBuildingFormWithFields() {
		$fields = [
			Field::create('username')->container('div'),
			Field::create('password')->attributes(['disabled' => 'disabled']),
			Field::create('free drink option')
				->options(
					['gin' => 'Spirits', 'beer' => 'Beer', 'wines' => 'Wine']
				)->value('gin'),
			Field::create('secret information')->type('hidden')->value(3),
		];

		$form = FormBuilder::create($fields)
			->method('get')
			->action('post.jsp')
			->attributes(['class' => 'brightpinkform']);

		$this->assertEquals(
			[
				'tag' => 'form',
				'attributes' => [
					'method' => 'get',
					'action' => 'post.jsp',
					'class' => 'brightpinkform'
				],
				'children' => [
					[
						'tag' => 'div',
						'children' => [
							[
								'tag' => 'input',
								'attributes' => [
									'type' => 'text',
									'name' => 'username',
								]
							]
						]
					],
					[
						'tag' => 'input',
						'attributes' => [
							'name' => 'password',
							'type' => 'password',
							'disabled' => 'disabled',
						]
					],
					[
						'tag' => 'select',
						'attributes' => [
							'name' => 'free drink option'
						],
						'children' => [
							[
								'tag' => 'option',
								'attributes' => [
									'value' => 'gin',
									'selected' => 'selected',
								],
								'children' => 'Spirits'
							],
							[
								'tag' => 'option',
								'attributes' => [
									'value' => 'beer',
								],
								'children' => 'Beer'
							],
							[
								'tag' => 'option',
								'attributes' => [
									'value' => 'wines',
								],
								'children' => 'Wine'
							],
						]
					],
					[
						'tag' => 'input',
						'attributes' => [
							'type' => 'hidden',
							'name' => 'secret information',
							'value' => 3,
						]
					],
				]
			],
			$form->form()
		);
	}

	public function testFieldsets() {
		$fields = [
			'login' => [
				Field::create('username'),
				Field::create('password'),
			],
			'details' => [
				Field::create('name'),
			]
		];

		$form = FormBuilder::create($fields)
			->method('get')
			->action('/submit');

		$this->assertEquals(
			[
				'tag' => 'form',
				'attributes' => [
					'method' => 'get',
					'action' => '/submit',
				],
				'children' => [
					[
						'tag' => 'fieldset',
						'children' => [
							[
								'tag' => 'legend',
								'children' => 'login'
							],
							[
								'tag' => 'input',
								'attributes' => [
									'type' => 'text',
									'name' => 'username'
								]
							],
							[
								'tag' => 'input',
								'attributes' => [
									'type' => 'password',
									'name' => 'password'
								]
							],
						]
					],
					[
						'tag' => 'fieldset',
						'children' => [
							[
								'tag' => 'legend',
								'children' => 'details'
							],
							[
								'tag' => 'input',
								'attributes' => [
									'type' => 'text',
									'name' => 'name'
								]
							],
						]
					],
				]
			],
			$form->form()
		);
	}
}