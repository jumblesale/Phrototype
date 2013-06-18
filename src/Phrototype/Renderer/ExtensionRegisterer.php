<?php

namespace Phrototype\Renderer;

use Phrototype\Logue;

class ExtensionRegisterer {

	// loadExtension by default
	public function __invoke($args) {
		return call_user_func_array([$this, 'loadExtension'], func_get_args());
	}
	public function loadExtension($obj) {
		if(gettype($obj) === 'string') {
			$obj = new $obj();
		}
		$class = get_class($obj);
		// This is hideous
		if(!in_array(
			'Phrototype\Renderer\iExtension',
				class_implements(
					get_class($obj)
				))
		) {
			Logue::log(
				"Failed loading $class: does not implement iExtension interface",
				Logue::WARN
			);
			return false;
		}

		if(!$obj->test()) {
			Logue::Log("Failed to load $class: test method returned falsey value", Logue::WARN);
			return false;
		}
		$name		= $obj->name();
		$details	= $obj->load();
		if(!($name && $details)) {
			throw new \Exception("Could not load extension $class: no name or details provided. Check the extension implements name() and load() methods correctly");
		}
		return $obj;
		/*if($this->loadMethod(
			$name, $details, $obj->render()
		)) {
			Logue::log("Successfully loaded renderer extension: $name", Logue::INFO);
			return true;
		}
		return false;*/
	}
}