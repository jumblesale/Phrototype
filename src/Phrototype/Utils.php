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

	public static function slashify($location) {
		if(!$location) {
			return;
		}
		if(substr($location, -1) != '/') {
			$location .= '/';
		}
		return $location;
	}

	public static function getDocumentRoot() {
		// Relies on this being three levels deep - brittle like a peanut
		return self::slashify(
			dirname(dirname(dirname(realpath(__FILE__))))
		);
	}
}