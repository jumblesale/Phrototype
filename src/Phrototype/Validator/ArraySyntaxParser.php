<?php

namespace Phrototype\Validator;

/**
 * ArraySyntaxParser
 * Converts HTML field names to array indexes
 * user[details][name] will look for
 * $array['user']['details']['name']
 */
class ArraySyntaxParser {
	public function parse(array $array, $name) {
		if(strpos($name, '[') === false) {
			return array_key_exists($name, $array) ?
				  $array[$name]
				: null;
		}
		$var = [];
		preg_match('/^([^\[]*)/', $name, $var);
		$keys = [];
		$value = $array[$var[1]];
		preg_match_all('/\[([^\]]+)\]/', $name, $keys);
		foreach($keys[1] as $entry) {
			$value = $value[$entry];
		}
		return $value;
	}
}