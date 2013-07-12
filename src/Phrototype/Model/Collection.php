<?php

namespace Phrototype\Model;

use Phrototype\Model;
use Phrototype\Writer;

class Collection implements
	\ArrayAccess,
	\Countable
{
	private $contents = [];
	private $ids = [];

	public function models() {
		return $this->contents;
	}

	public function count() {
		return count($this->contents);
	}

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
			   is_a($value, '\Phrototype\Prototype')
			&& $value->id
		) {
			$this->contents[] = $value;
			$this->ids[$value->id] = count($this->contents) - 1;
		} else {
			$index = count($this->ids);
			$this->ids[$index] = $index;
			$this->contents[$index] = $value;
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

	public function add(\Phrototype\Prototype $model) {
		$this->offsetSet(null, $model);
		return $this;
	}

	public function toArray() {
		$arrays = [];
		foreach($this->contents as $model) {
			$arrays[] = $model->toArray();
		}
		return $arrays;
	}

	public function save($location) {
		$w = new Writer();
		$w->write($location, '');
		$w->write(
			$location,
			json_encode($this->toArray(), JSON_PRETTY_PRINT),
			true
		);
	}
}