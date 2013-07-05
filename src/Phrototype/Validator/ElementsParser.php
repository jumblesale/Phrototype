<?php

namespace Phrototype\Validator;

class ElementsParser {
	public function parse($elements) {
		$html = '';
		foreach($elements as $element) {
			$html .= $this->element($element);
			if(array_key_exists('children', $element)) {
				$children = $element['children'];
				$html .= '>';
				if(is_array($children)) {
					$html .= $this->parse($element['children']);
				} else {
					$html .= $children;
				}
				$html .= '</' . $element['tag'] . '>';
			} else {
				$html .= ' />';
			}
		}
		return $html;
	}

	public function element($element) {
		$attributes = [];
		if(array_key_exists('attributes', $element)) {
			foreach($element['attributes'] as $attrName => $value) {
				$attributes[] = "$attrName=\"$value\"";
			}
		}
		$html = '<' . implode(' ', [
			$element['tag'], implode(' ', $attributes)
		]);
		return $html;
	}
}