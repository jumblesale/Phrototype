<?php

namespace Phrototype\Validator;

use Phrototype\Validator\Field;
use Phrototype\Validator\FormParser;
use Phrototype\Utils;

// Given a bunch of fields, make me a form!
class Form {
	// These are HTML elements perhaps you recognise them
	private static $types = [
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
			'tag' => 'textarea',
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
	private $fields = [];
	private $submit;
	private $submitAttributes;
	private $method;
	private $action;
	private $attributes = [];
	private $errors;
	private $errorContainer = 'div';
	private $errorAttributes = [];

	private $parser;

	public function __construct(array $fields = array()) {
		$this->fields = $fields;
		$this->parser = new FormParser();
	}

	public static function create(array $fields = array()) {
		return new Form($fields);
	}

	public function fields(array $fields = null) {
		if($fields) {
			$this->fields = $fields;
			return $this;
		}
		return $this->fields;
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

	public function submit($v = null, $attr = null) {
		if($v !== null) {
			$this->submit = $v;
			$this->submitAttributes = $attr;
			return $this;
		}
		return $this->submit;
	}

	public function attributes(array $v = null) {
		if($v !== null) {
			$this->attributes = $v;
			return $this;
		}
		return $this->attributes;
	}

	public function errors(array $v = array()) {
		if($v) {
			$this->errors = $v;
			return $this;
		}
		return $this->errors;
	}

	public function errorContainer($v = null) {
		if($v) {
			$this->errorContainer = $v;
			return $this;
		}
		return $this->errorContainer;
	}

	public function errorAttributes(array $v = array()) {
		if($v) {
			$this->errorAttributes = $v;
			return $this;
		}
		return $this->errorAttributes;
	}

	public function submitAttributes() {
		return $this->submitAttributes;
	}

	public static function tags() {
		$tags = [];
		foreach(self::$types as $type => $details) {
			$tags[$details['tag']] = $details['tag'];
		}
		return $tags;
	}

	public static function resolveType(Field $field) {
		if(
			$field->type()
			&& in_array(
				$field->type(), array_keys(self::$types)
			)
		) {
			return $field->type();
		}
		if(
			$field->name()
			&& in_array(
				$field->name(), array_keys(self::$types)
			)
		) {
			return $field->name();
		}
		if($field->options()) {
			return 'select';
		}
		return 'input';
	}

	public static function types() {
		return self::$types;
	}

	public function html($elements = array()) {
		$elements = $elements ?: $this;
		$html =$this->parser->parse($elements)->saveHtml();
		return $html;
	}
}