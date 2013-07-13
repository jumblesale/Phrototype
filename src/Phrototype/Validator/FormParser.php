<?php

namespace Phrototype\Validator;

use Phrototype\Validator\FormBuilder;
use Phrototype\Validator\Field;
use Phrototype\Utils;

class FormParser {
	private $dom;
	private $fields = [];
	private $form;
	private $fieldsets;

	public function __construct() {
		$this->dom = new \DOMDocument();
	}

	private function isField($field) {
		return (
			   gettype($field) == 'object'
			&& is_a($field, 'Phrototype\Validator\Field')
		);
	}

	private function isForm($form) {
		return (
			   gettype($form) == 'object'
			&& is_a($form, 'Phrototype\Validator\Form')
		);
	}

	public function parse($element) {
		if($this->isForm($element)) {
			$this->dom->appendChild($this->parseForm($element));
			return $this->dom;
		}
		if($this->isField($element)) {
			$this->dom->appendChild($this->parseField($element));
			return $this->dom;
		}
		if(is_array($element)) {
			$nodes = [];
			// Check if it's a fieldset
			if(Utils::isHash($element)) {
				$nodes = $this->parseFieldset($element);
			} else {
				$nodes = $this->parseFields($element);
			}
			foreach($nodes as $node) {
				$this->dom->appendChild($node);
			}
			return $this->dom;
		}
		return false;
	}

	public function parseFieldset($fieldset) {
		$nodes = [];
		foreach($fieldset as $legend => $fields) {
			$node = $this->dom->createElement('fieldset');
			$legendNode = $this->dom->createElement('legend');
			$legendNode->appendChild($this->dom->createTextNode(
				$legend
			));
			$node->appendChild($legendNode);
			$fieldNodes = $this->parseFields($fields);
			foreach($fieldNodes as $fieldNodes) {
				$node->appendChild($fieldNodes);
			}
			$nodes[] = $node;
		}
		return $nodes;
	}

	public function parseForm($form) {
		$node = $this->dom->createElement('form');
		$node->setAttribute('method', $form->method());
		$node->setAttribute('action', $form->action());
		$this->dom->appendChild($node);
		$fields;
		// fields could be a hash of group => fields pairs
		if(Utils::isHash($form->fields())) {
			$fieldsets = $this->parseFieldset($form->fields());
			$fields = $fieldsets;
		} else {
			$fields = $this->parseFields($form->fields());
		}
		foreach($fields as $field) {
			$node->appendChild($field);
		}
		$submit = $this->dom->createElement('input');
		$submit->setAttribute('type', 'submit');
		$submit->setAttribute('value', $form->submit() ?: 'Submit');
		if($form->submitAttributes()) {
			foreach($form->submitAttributes() as $name => $value) {
				$submit->setAttribute($name, $value);
			}
		}
		$node->appendChild($submit);
		return $node;
	}

	public function parseFields($fields) {
		$nodes = [];
		foreach($fields as $field) {
			$nodes[] = $this->parseField($field);
		}
		return $nodes;
	}

	public function parseField($field) {
		if(!$this->isField($field)) {
			throw new \Exception('Attempt to parse a non-Field object failed');
		}
		$type = $field->type() ?: 'input';
		$node = $this->dom->createElement($type);
		$node->setAttribute('name', $field->name());
		if('select' == $type) {
			$options = $this->parseOptions($field->options(), $field->value());
			foreach($options as $option) {
				$node->appendChild($option);
			}
		}
		$node->setAttribute('name', $field->name());
		foreach($field->attributes() as $name => $value) {
			$node->setAttribute($name, $value);
		}
		if($field->container()) {
			$container = $this->dom->createElement($field->container()['tag']);
			foreach($field->container()['attributes'] as $name => $value) {
				$container->setAttribute($name, $value);
			}
			$container->appendChild($node);
			$node = $container;
		}
		if($field->description()) {
			$label = $this->parseLabel($field->description(), $field->name());
			$label->appendChild($node);
			$node = $label;
		}
		return $node;
	}

	public function parseLabel($label, $for = null) {
		$node = $this->dom->createElement('label');
		$node->setAttribute('for', $for);
		$node->appendChild($this->dom->createTextNode($label));
		return $node;
	}

	public function parseOptions($options, $selectedValue = null) {
		$optionNodes = [];
		foreach($options as $value => $text) {
			$option = $this->dom->createElement('option');
			$option->setAttribute('value', $value);
			if($selectedValue && $value == $selectedValue) {
				$option->setAttribute('selected', 'selected');
			}
			$option->appendChild($this->dom->createTextNode($text));
			$optionNodes[] = $option;
		}
		return $optionNodes;
	}
}