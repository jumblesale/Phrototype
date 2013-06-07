<?php

namespace Phrototype\Curry;

use Phrototype\Curry\PartialArg;

class Bind {
	public function __invoke($args) {
		return call_user_func_array($self::curry, func_get_args());
	}

	public static function curry($fn, $arg, $obj = null) {
		return function() use ($fn, $arg, $obj) {
			$args = func_get_args();
			array_unshift($args, $arg);
			if($obj) {
				$fn = array($obj, $fn);
			}
			return call_user_func_array($fn, $args);
		};
	}

	public static function partial($fn) {
		$partialArgs = array_slice(func_get_args(), 1);
		return function() use ($fn, $partialArgs) {
			$fnArgs = func_get_args();
			$args = [];
			$i = 0;
			foreach($partialArgs as $arg) {
				$args[] = (gettype($arg) == 'object'
						&& get_class($arg) == 'Phrototype\Curry\PartialArg') ?
					array_shift($fnArgs) :
					$partialArgs[$i];
				$i += 1;
			}
			return call_user_func_array($fn, $args);
		};
	}

	public static function …() {return new PartialArg();}

	// Alias to … for people too boring to use …
	// or portability or somesuch
	public static function ___() {return new PartialArg();}
}