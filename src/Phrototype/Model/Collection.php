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
			$index = max(array_values($this->ids)) + 1;
			$this->ids[] = $index;
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
				$this->ids[] = $index;
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

	public function unshift(\Phrototype\Prototype $model) {
		// If the objects have their own ids this is not a possible operation
		if(!array_keys($this->ids) == array_values($this->ids)) {
			throw new \Exception('Tried to unshift onto a collection with ' . 
				' indexed ids. Panicking.');
		}
		$ids = [];
		$contents = [];
		array_map(function($v) use(&$ids, &$contents) {
			$ids[] = $v + 1;
			$contents[$v + 1] = $this->contents[$this->ids[$v]];
		}, $this->ids);
		array_unshift($ids, min(array_keys($contents)) - 1);
		array_unshift($contents, $model);
		$this->ids = $ids;
		$this->contents = $contents;
		return $this;
	}

	public function reverse(Collection $collection = null) {
		if(!$collection) {
			$collection = $this;
		}
		$ids = [];
		foreach($this->ids as $index => $id) {
			array_unshift($ids, $id);
		}
		$this->ids = $ids;
		return $this;
	}

	public function find($field, $value) {
		$matches = [];
		foreach($this->ids as $index => $id) {
			$model = $this->contents[$id];
			if($model->$field === $value) {
				$matches[$id] = $model;
			}
		}
		if($matches) {
			return new Collection($matches);
		}
		return false;
	}

	/**
	 * order
	 * Sort the collection by a column
	 *
	 * @param column string The column to order by
	 * @param ascending bool True to sort ascending, false to sort descending
	 * @return \Phrototype\Model\Collection
	 */
	public function order($column, $ascending = true) {
		$fn = $ascending ? 'asort' : 'arsort';
		$ids = [];
		$values = [];
		foreach($this->ids as $index => $id) {
			$model = $this->contents[$id];
			$values[$id] = $model->$column;
		}
		$fn($values);
		foreach($values as $id => $value) {
			$ids[] = $id;
		}
		$this->ids = $ids;
		return $this;
	}
}