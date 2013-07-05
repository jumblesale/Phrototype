<?php

namespace Phrototype;

use Phrototype\Validator\Field;
use Phrototype\Validator\FormBuilder;

class Validator {
	private $fields;
	private $groups;
	private $currentGroup;
	private $messages;
	private $form;

	public function __construct() {
		$this->form = new FormBuilder();
	}

	public function form() {
		return $this->form;
	}

	public function group($name) {
		$this->currentGroup = $name;
		return $this;
	}

	public function groups() {
		return $this->groups;
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
		$success = true;
		foreach($this->fields as $name => $field) {
			$messages[$name] = [];
			$value = array_key_exists($name, $data) ?
				  $data[$name]
				: null;
			if($field->required() && $value === null) {
				$success = false;
				array_push($messages[$name], "$name is a required field");
			} else {
				$success = $success && $field->validate($value);
				$messages[$name] = $field->messages();
			}
		}
		$this->messages = $messages;
		return $success;
	}

	public function html() {
		$fields = $this->fields;
		$groups = $this->groups;
		if($groups) {
			$fieldsets = [];
			foreach($groups as $name => $fields) {
				foreach($fields as $field => $v) {
					$fieldsets[$name][] = $fields[$field];
				}
			}
			$fields = $fieldsets;
		}
		return $this->form()->fields($this->fields)->html();
	}
}