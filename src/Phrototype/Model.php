<?php

namespace Phrototype;

use Phrototype\Prototype;
use Phrototype\Writer;
use Phrototype\Utils;

class Model extends Prototype {
	/**
	 * save
	 * Save a JSON representation of this model to a file
	 * @param location string The file to save to
	 */
	public function save($location) {
		$w = new Writer();
		$w->write($location, json_encode($this->toArray()));
	}

	/**
	 * toArray
	 * Get the array representation of this model
	 * Note: this won't marshal any functions. Sorry :(
	 * @returns array
	 */
	public function toArray() {
		$array = [];
		foreach($this->properties as $name => $value) {
			$array[$name] = $value;
		}
		return $array;
	}

	/**
	 * load
	 * Load a collection of models
	 *
	 * @param data mixed An array of data; or, a string giving the location of
	 * a file containing JSON data
	 * @returns A new \Phrototype\Model on success or false if the model could not
	 * be created
	 */
	public static function load($data = null) {
		if(is_string($data)) {
			$w = new Writer();
			$json;
			try {
				$json = $w->read($data);
			} catch(\Exception $e) {
				throw new \Exception("Unable to load model from $data,"
					. " location in unreachable with " . $e->getMessage());
			}
			$json = json_decode($json, true);
			if(!$json) {
				throw new \Exception("JSON in $data is malformed");
				return false;
			}
			return self::forge($json);
		}
		$obj = [];
		foreach($data as $name => $value) {
			$obj[$name] = $value;
		}

		return self::forge($obj);
	}

	/**
	 * forge
	 * Create a new model
	 *
	 * @param data array An aray of data to use
	 * @param prototype object An object to use as the model's prototype
	 */
	public static function forge(array $data = array(), $prototype = null) {
		return Prototype::create($data, $prototype, '\Phrototype\Model');
	}
}