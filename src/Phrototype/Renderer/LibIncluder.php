<?php

namespace Phrototype\Renderer;

use Phrototype\Utils;

class LibIncluder {
	private $libs = [
		'normalize' => [
			'url' => 'http://yui.yahooapis.com/3.10.3/build/cssnormalize/cssnormalize-min.css',
			'type' => 'css',
		],
		'jquery' => [
			'url' => 'http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js',
			'type' => 'js',
		],
	];
	private $imports = [];

	public function import($lib, $location = null) {
		if(is_array($lib)) {
			$links = [];
			foreach($lib as $link) {
				if(is_array($link)) {
					$links[] = $this->import(
						array_keys($link)[0],
						array_values($link)[0]
					);
				} else {
					$links[] = $this->import($link);
				}
			}
			return implode("\n", $links);
		}

		$url;
		$type;
		if(array_key_exists($lib, $this->libs)) {
			$type = $this->libs[$lib]['type'];
			$url = $this->libs[$lib]['url'];
		} else {
			$type = Utils::getFileExtension($location);
			$url = $location;
		}

		if($type == 'css') {
			$link = $this->generateCss($url);
		} else {
			$link = $this->generateJs($url);
		}
		$this->imports[$lib] = null;
		return $link;
	}

	private function generateCss($url) {
		return '<link rel="stylesheet" type="text/css" href="' . $url . '">';
	}

	private function generateJs($url) {
		return '<script type="text/javascript" src="' . $url . '"></script>';
	}
}