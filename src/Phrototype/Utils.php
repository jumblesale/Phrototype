<?php

namespace Phrototype;

class Utils {
	public static function isHash($array) {
		if(!is_array($array)) {
			return false;
		}
		// thanks SO!
		return (bool)count(array_filter(array_keys($array), 'is_string'));
	}

	public static function getFileExtension($file) {
		return pathinfo($file, PATHINFO_EXTENSION) ?: false;
	}
}