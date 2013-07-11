<?php

namespace Phrototype\Tests;

use Phrototype;

class GoTest extends \PHPUnit_Framework_TestCase {
	public function testRouting() {
		$app = new Phrototype\App();

		$app->router()->post('/true', function() use($app) {
			return 'it is true; i hath been rendered';
		});

		$this->assertFalse(
			$app->go('post', '/nottrue')
		);

		$this->assertEquals(
			'it is true; i hath been rendered',
			$app->go('post', '/true')
		);
	}
}