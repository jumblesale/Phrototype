<?php

namespace Phrototype\Model;

use Phrototype\Model;

class Collection implements \ArrayAccess {
	private $contents = [];
	private $ids = [];

	public function offsetExists($offset) {
		return in_array($offset, array_keys($this->ids));
	}

	public function offsetGet($offset) {
		if(!$this->offsetExists($offset)) {
			throw new \Exception("Undefined offset: $offset");
		}
		return $this->contents[$this->ids[$offset]];
	}
	public function offsetSet($offset, $value) {
		if(
			   is_array($value)
			&& array_key_exists('id', $value)
		) {
			$this->ids[$value['id']] = $value;
		} else {
			$this->ids[] = $value;
			$this->contents[count($this->ids)] = $value;
		}
	}
	public function offsetUnset($offset) {
		unset($this->contents[$this->ids[$offset]]);
		unset($this->ids[$offset]);
	}

	public function __construct(array $models = array()) {
		$this->contents = $models;
		$this->populateIds($models);
	}

	public function ids() {
		return $this->ids;
	}

	private function populateIds($models) {
		// ALL members must have ids for this to work
		$allModelsHaveIds = true;
		foreach($models as $model) {
			if(!$model->id) {
				$allModelsHaveIds = false;
				// break considered EXTREMELY USEFUL
				break;
			}
		}
		foreach ($models as $index => $model) {
			if($allModelsHaveIds) {
				$this->ids[$model->id] = $index;
			} else {
				$this->ids[$index] = $index;
			}
		}
	}

	public function toArray() {
		$models = [];
		foreach($this->ids as $id => $index) {
			$models[$id] = $this->contents[$index];
		}
		return $models;
	}
}