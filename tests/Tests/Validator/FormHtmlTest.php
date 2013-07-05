<?php

namespace Phrototype\Tests;

use Phrototype\Validator\Field;
use Phrototype\Validator\FormBuilder;

class FormHtmlTest extends \PHPUnit_Framework_TestCase {
	public function testInput() {
		$form = FormBuilder::create([
			Field::create('username')
		]);

		$this->assertEquals(true, true);
	}
}