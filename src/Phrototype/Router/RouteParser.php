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
		if($this->verb() == 'get') {
			return $_GET;
		}
		if($this->verb() == 'post') {
			return $_POST;
		}
		return null;
	}

	public function path() {
		return $this->request['PHP_SELF'];
	}
}