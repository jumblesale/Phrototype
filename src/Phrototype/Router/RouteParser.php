<?php

namespace Phrototype\Router;

class RouteParser {
	private $request;

	public function __construct($request) {
		$this->request = $request;
	}

	public function request($v = null) {
		if(null != $v) {
			$this->request = $v;
			return $this;
		}
		return $this->request;
	}

	public function verb() {
		return strtolower($this->request['REQUEST_METHOD']);
	}

	public function query() {
		// This feels inelegant
		$parts = explode('&', $this->request['QUERY_STRING']);
		$hash = [];
		foreach($parts as $part) {
			$values = explode('=', $part);
			$hash[$values[0]] = $values[1];
		}
		return $hash;
	}

	public function path() {
		return $this->request['PATH_INFO'];
	}
}