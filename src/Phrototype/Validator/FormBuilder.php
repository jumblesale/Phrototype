<?php

namespace Phrototype\Validator;

use Phrototype\Validator\Field;
use Phrototype\Validator\ElementsParser;
use Phrototype\Utils;

// Given a bunch of fields, make me a form!
class FormBuilder {
	// These are HTML elements perhaps you recognise them
	private $types = [
		  'input'		=> [
		  	'tag' => 'input',
		  	'attributes' => ['type' => 'text'],
		]
		, 'hidden'		=> [
			'tag' => 'input',
			'attributes' => ['type' => 'hidden']
		]
		, 'checkbox'	=> ['tag' => 'checkbox']
		, 'radio'		=> ['tag' => 'radio']
		, 'password'	=> [
			'tag' => 'input',
			'attributes' => ['type' => 'password']
		]
		, 'text'		=> [
			'input',
			'attributes' => ['type' => 'text']
		]
		, 'submit'		=> [
			'tag' => 'input',
			'attributes' => ['type' => 'submit']
		]
		, 'select'		=> ['tag' => 'select']
		, 'email'		=> [
			'tag' => 'input',
			'attributes' => ['type' => 'email']
		]
	];
	private $form = [];
	private $fields;
	private $method;
	private $action;
	private $attributes = [];

	private $parser;

	public function __construct(array $fields = array()) {
		$this->fields = $fields;
		$this->parser = new ElementsParser();
	}

	public static function create(array $fields = array()) {
		return new FormBuilder($fields);
	}

	public function fields(array $fields = null) {
		if($fields) {
			$this->fields = $fields;
			$this->form();
			return $this;
		}
		return $this->fields;
	}

	public function form() {
		$this->form = $this->buildForm($this->fields);
		return $this->form;
	}

	public function method($method = null) {
		if($method) {
			$this->method = $method;
			return $this;
		}
		return $this->method;
	}

	public function action($action = null) {
		if($action) {
			$this->action = $action;
			return $this;
		}
		return $this->action;
	}

	public function attributes(array $v = null) {
		if($v !== null) {
			$this->attributes = $v;
			return $this;
		}
		return $this->attributes;
	}

	public function buildForm(array $fields = array()) {
		$form = ['tag' => 'form'];
		$form['attributes'] = array_merge(
			$this->attributes(),
			['method' => $this->method(), 'action' => $this->action()]
		);
		if(!Utils::isHash($fields)) {
			foreach($fields as $field) {
				$form['children'][] = $this->buildElement($field);
			}
			return $form;
		}
		// Deal with fieldsets
		foreach($fields as $name => $fieldset) {
			$fieldsetTag = [
				'tag' => 'fieldset',
				'children' => [
					['tag' => 'legend', 'children' => $name]
				]
			];
			foreach($fieldset as $setChild) {
				$fieldsetTag['children'][] = $this->buildElement($setChild);
			}
			$form['children'][] = $fieldsetTag;
		}
		return $form;
	}

	public function buildElement($field) {
		$attributes;
		$type = $this->resolveType($field);
		$tag = $this->types[$type]['tag'];
		$attributes = [];
		if(array_key_exists('attributes', $this->types[$type])) {
			$attributes = array_merge(
				$field->attributes(),
				$this->types[$type]['attributes']
			);
		} else {
			$attributes = $field->attributes();
		}

		$attributes['name'] = $field->name();
		$value = $field->value();
		$element = [
			'tag' => $this->types[$type]['tag'],
			'attributes' => $attributes
		];

		if('select' == $type) {
			$children = $this->buildSelectOptions($field->options(), $value);
			$element['children'] = $children;
		} else {
			if(null !== $value) {
				$element['attributes']['value'] = $value;
			}
		}
		
		if($field->container()) {
			$container = ['tag' => $field->container()['tag']];
			$container['children'][] = $element;
			if($field->container()['attributes']) {
				$container['attributes'] = $field->container()['attributes'];
			}
			$element = $container;
		}

		return $element;
	}

	public function buildSelectOptions($options, $defaultValue) {
		$return = [];
		foreach($options as $value => $text) {
			$hash = [
				'tag' => 'option',
				'attributes' => ['value' => $value],
				'children' => $text,
			];
			if($defaultValue === $value) {
				$hash['attributes']['selected'] = 'selected';
			}
			$return[] = $hash;
		}
		return $return;
	}

	public function resolveType(Field $field) {
		if(
			$field->type()
			&& in_array(
				$field->type(), array_keys($this->types)
			)
		) {
			return $field->type();
		}
		if(
			$field->name()
			&& in_array(
				$field->name(), array_keys($this->types)
			)
		) {
			return $field->name();
		}
		if($field->options()) {
			return 'select';
		}
		return 'input';
	}

	public function html(array $elements = array()) {
		if(!$elements) {
			$elements = [$this->form()];
		}
		return $this->parser->parse($elements);
	}
}