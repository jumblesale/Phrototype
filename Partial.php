<?php

echo '<pre>';

function partial($fn) {
	$partialArgs = array_slice(func_get_args(), 1);
	return function() use ($fn, $partialArgs) {
		$fnArgs = func_get_args();
		$args = array();
		$i = 0;
		foreach($partialArgs as $arg) {
			$args[] = (gettype($arg) == 'object'
					&& get_class($arg) == 'PartialArg') ?
				array_shift($fnArgs) :
				$partialArgs[$i];
			$i += 1;
		}
		return call_user_func_array($fn, $args);
	};
}

function …() {return new PartialArg();}

class PartialArg {}

$crypt = partial('crypt', …(), 'Push It');

$password = $crypt('wrongponyfreerangeunusual');

echo "$password\n";
echo crypt('wrongponyfreerangeunusual', 'Push It');