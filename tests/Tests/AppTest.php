<?php

namespace Phrototype\Tests;

use Phrototype;

class AppTest extends \PHPUnit_Framework_TestCase {
	public function testAppCanProduceViewPage() {
		$data = [
			[
				'title' => 'welcome to phrototype!',
				'content' => 'a rapid prototyping framework for php',
			],
			[
				'title' => 'what\'s going on here?',
				'content' => 'all things are automatically created! it is breathtaking.',
			],
		];

		$app = new \Phrototype\App();
		$app->route()->get('blog/view', function() use ($app, $data) {
			return $app->view($data);
		});
		
		echo "\n\n"; print_r($app->route()->dispatch('get', 'blog/view')); echo "\n\n";
	}
}