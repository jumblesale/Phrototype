<?php

namespace Phrototype;

class Writer {
	private $baseDir;
	public function __construct($baseDir = null) {
		$this->baseDir = Utils::slashify(Utils::getDocumentRoot()). $baseDir;
	}

	public function getBaseDir() {
		return $this->baseDir;
	}

	public function write($location, $data = null) {
		$location = Utils::slashify($this->baseDir) . $location;
		if(!is_writable(dirname($location))) {
			throw new \Exception("Could not write to $location");
		}
		$flags = null;
		if(file_exists($location)) {
			$flags =  FILE_APPEND;
		}
		return file_put_contents($location, $data, $flags);
	}

	public function read($location = null) {
		$location = $location ?
			  $this->baseDir . $location
			  // strip trailing slash
			: $this->baseDir;
		// Maybe add support for reading as arrays?
		return file_get_contents($location);
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