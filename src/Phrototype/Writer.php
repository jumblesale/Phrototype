<?php

namespace Phrototype;

class Writer {
	private $baseDir;
	public function __construct($baseDir = null) {
		$baseDir = Utils::slashify($baseDir);
		$this->baseDir = Utils::getDocumentRoot() . '/' . $baseDir;
	}

	public function getBaseDir() {
		return $this->baseDir;
	}

	public function write($location, $data = null) {
		$location = $this->baseDir . $location;
		if(!is_writable(dirname($location))) {
			throw new \Exception("Could not write to $location");
		}
		$flags = null;
		if(file_exists($location)) {
			$flags =  FILE_APPEND;
		}
		return file_put_contents($location, $data, $flags);
	}

	public function read($location) {
		// Maybe add support for reading as arrays?
		return file_get_contents($this->baseDir . $location);
	}

	public function purge($location = null) {
		$location = Utils::slashify($this->baseDir . $location);
		foreach (new \DirectoryIterator($location) as $fileInfo) {
			if(!$fileInfo->isDot()) {
				unlink($location . $fileInfo->getFilename());
			}
		}
	}
}