<?php

namespace Phrototype\Tests;

use Phrototype;
use Phrototype\Model;
use Phrototype\Validator;

class AddTest extends \PHPUnit_Framework_TestCase {
	public function testAppCanProduceViewPage() {
		$data = Model\Factory::create(
			['title', 'content']
		)->load([
			[
				'title' => 'welcome to phrototype!',
				'content' => 'a rapid prototyping framework for php',
			],
			[
				'title' => 'what\'s going on here?',
				'content' => 'all things are automatically created! it is breathtaking.',
			],
		]);

		$app = new \Phrototype\App();
		$app->router()->get('blog/view', function() use ($app, $data) {
			return $app->view($data);
		});
		
		$this->assertNotNull($app->router()->dispatch('get', 'blog/view'));
	}

	public function testAdd() {
		$validator = new Validator();
	}
}