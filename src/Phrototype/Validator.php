<?php

namespace Phrototype;

use Phrototype\Validator\Field;
use Phrototype\Validator\Form;
use Phrototype\Validator\ArraySyntaxParser;

class Validator {
	private $fields;
	private $groups;
	private $titles = [];
	private $currentGroup;
	private $messages;
	private $form;
	private $data;
	private $arrayParser;

	public function __construct() {
		$this->form = new Form();
		$this->arrayParser = new ArraySyntaxParser();
	}

	public static function create() {
		return new Validator();
	}

	public function form() {
		return $this->form;
	}

	public function group($name, $title = '') {
		$this->currentGroup = $name;
		if($title) {
			$this->titles[$name] = $title;
		}
		return $this;
	}

	public function groups() {
		return $this->groups;
	}

	public function getGroupTitle($name) {
		if(array_key_exists($name, $this->titles)) {
			return $this->titles[$name];
		} else {
			return $name;
		}
	}

	public function fields() {
		return $this->fields;
	}

	public function messages($name) {
		if($name) {
			return $this->messages[$name];
		}
		return $this->messages;
	}

	public function field($name, $type = null) {
		if($this->currentGroup) {
			$this->groups[$this->currentGroup][] = $name;
		}
		$field = new Field($name, $type);
		$this->fields[$name] = $field;
		return $field;
	}

	public function validate($data) {
		$messages = [];
		$values = [];
		$success = true;
		foreach($this->fields as $name => $field) {
			$messages[$name] = [];
			$value = $this->arrayParser->parse($data, $name);
			Logue::Log("Validating field $name with "
				. ($value ? "value: $value" : 'no value'), Logue::DEBUG);
			if($field->required() && !$field->nullable() && $value == null) {
				$success = false;
				array_push($messages[$name], "$name is a required field");
			} else {
				$success = $success && $field->validate($value);
				if($field->messages()) {
					$messages[$name] = $field->messages();
				}
			}
			$values[$name] = $value;
		}
		$this->messages = $messages;
		$this->data = $values;
		return $success;
	}

	public function data() {
		return $this->data;
	}

	public function html() {
		$fields = $this->fields;
		$groups = $this->groups;
		if($groups) {
			$fieldsets = [];
			foreach($groups as $name => $groupFields) {
				$title = $this->getGroupTitle($name);
				foreach($groupFields as $field => $v) {
					$fieldsets[$title][] = $fields[$v];
				}
			}
			$this->form->fields($fieldsets);
		}
		return $this->form()->html();
	}
}