<?php

namespace Phrototype\Tests;

use Phrototype;

class AppTest extends \PHPUnit_Framework_TestCase {
	public function testAppCanProduceViewPage() {
		$this->markTestIncomplete('Not yet');

		$data = [
			'title' => 'welcome to phrototype!',
			'content' => 'a rapid prototyping framework for php'
		];

		$app = new \Phrototype\App();
		$app->get('blog/view', function() use ($app, $data) {
			return $app->render('json', $data);
		});

		$this->assertEquals(
			json_encode($data),
			$app->dispatch('get', 'blog/view')
		);
	}
}