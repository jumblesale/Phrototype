<?php

namespace Phrototype\Validator;

use Phrototype\Validator\FormBuilder;
use Phrototype\Validator\Field;
use Phrototype\Utils;

class FormParser {
	public function parse($element) {
		if(
			   gettype($element) == 'object'
			&& is_a($element, 'Phrototype\Validator\Form')
		) {
			return $this->parseForm($element);
		}
		if(
			   gettype($element) == 'object'
			&& is_a($element, 'Phrototype\Validator\Field')
		) {
			return $this->parseField($element);
		}
		if(is_array($element)) {
			// Check if it's a fieldset
			if(Utils::isHash($element)) {
				return $this->parseFieldset($element);
			}
			$dom = new \DOMDocument();
			foreach($element as $field) {
				$dom->appendChild(
					$dom->importNode(
						$this->parseField($field)->documentElement
					)
				);
			}
			return $dom;
		}
		return false;
	}

	public function parseForm($form) {
		$dom = new \DOMDocument();
		$formNode = $dom->createElement('form');
		$formNode->setAttribute('action', $form->action());
		$formNode->setAttribute('method', $form->method());
		foreach($form->attributes() as $name => $value) {
			$formNode->setAttribute($name, $value);
		}
		foreach($form->fields() as $field) {
			if($field->description()) {
				$label = $dom->createElement('label');
				$label->setAttribute('for', $field->name());
				$label->appendChild(
					$dom->createTextNode($field->description())
				);
				$formNode->appendChild($label);
			}
			$fieldNode = $this->parseField($field);
			$formNode->appendChild(
				$dom->importNode($fieldNode->documentElement, true)
			);
		}
		$dom->appendChild($formNode);
		return $dom;
	}

	public function parseField($field) {
		$dom = new \DOMDocument();
		$type = Form::types()[Form::resolveType($field)];
		$fieldNode = $dom->createElement($type['tag']);
		$fieldNode->setAttribute('name', $field->name());
		foreach($field->attributes() as $name => $value) {
			$fieldNode->setAttribute($name, $value);
		}
		if(array_key_exists('attributes', $type)) {
			foreach($type['attributes'] as $name => $value) {
				$fieldNode->setAttribute($name, $value);
			}
		}
		if($field->container()) {
			$details = $field->container();
			$container = $dom->createElement($details['tag']);
			foreach($details['attributes'] as $name => $value) {
				$container->setAttribute($name, $value);
			}
			$container->appendChild($fieldNode);
			$fieldNode = $container;
		}
		if($type['tag'] == 'select') {
			foreach($field->options() as $value => $name) {
				$option = $dom->createElement('option');
				$option->setAttribute('value', $value);
				$option->appendChild($dom->createTextNode($name));
				$fieldNode->appendChild($option);
			}
		} else {
			if($field->value()) {
				$fieldNode->setAttribute('value', $field->value());
			}
		}
		$dom->appendChild($fieldNode);
		return $dom;
	}

	public function parseFieldset($fieldset) {
		$dom = new \DOMDocument();
		foreach($fieldset as $name => $fields) {
			$fieldset = $dom->createElement('fieldset');
			$legend = $dom->createElement('legend');
			$fieldset->appendChild($legend);
			$legend->appendChild($dom->createTextNode($name));
			$parsedFields = $this->parse($fields);
			foreach($parsedFields->childNodes as $node) {
				$fieldset->appendChild($dom->importNode($node));
			}
			$dom->appendChild($fieldset);
		}
		return $dom;
	}
}