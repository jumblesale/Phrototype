<?php

namespace Phrototype\Validator;

use Phrototype\Validator\Field;

// Given a bunch of fields, make me a form!
class FormBuilder {
	// These are HTML elements perhaps you recognise them
	private $types = [
		  'checkbox'	=> ['tag' => 'checkbox']
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

	public function build(array $fields) {
		foreach($fields as $field) {
			$type	= $this->types[$this->resolveType($field)];
			$tag	= $types[$type]['tag'];
			$attributes = [];
			if(array_key_exists('attributes', $type)) {
				$attributes = array_merge(
					$field->attributes(),
					$type['attributes']
				);
			} else {
				$attributes = $field->attributes();
			}
			$hash['attributes']	= $this->resolveAttributes($field);
			$hash['contents']	= $this->resolveContents($field);
		}
		return $hash;
	}

	public function resolveType($field) {
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
				$field->type(), array_keys($this->types)
			)
		) {
			return $field->name();
		}
		if($field->options()) {
			return 'select';
		}
		return 'input';
	}
}