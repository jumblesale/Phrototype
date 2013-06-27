<?php

namespace Phrototype\Tests\Model;

use Phrototype\Model;

class ModelTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$this->will = Model\Factory::create(
			['name' => 'Will', 'email' => 'will@norman.dy',]
		);
		$this->harold = Model\Factory::create(
			['name' => 'Harold', 'email' => 'harry@royal.gov.uk',]
		);

		$this->blog = Model\Factory::create([
			'title', 'date-published', 'author', 'content'
		])->load([
			['title' => 'the hunt for the best sort of dog, day one',
			 'date-published' => '1066-09-29',
			 'author' => $this->will,
			 'content' => implode("\n\n", [
				'I have arrived in England to find the best sort of dog.',
				'So far only light resistance.',
				'No dogs to report at this time.',
			])],
			['title' => 'that scoundrel will not get my dogs',
			 'date-published' => '1066-10-12',
			 'author' => $this->harold,
			 'content' => implode("\n\n", [
				'That William fellow is trying to get at our dogs.',
				'I won\'t stand for it!',
				'Have at thee!',
			])],
			['title' => 'the hunt for the best sort of dog, day fourteen',
			 'date-published' => '1066-10-13',
			 'author' => $this->will,
			 'content' => implode("\n\n", [
				'I\'ve narrowed my list down to to sorts of dogs:',
				"1.pug\n1.french bulldog",
				'Hang on, got to go and sort something out...'
			])],
			['title' => 'the hunt for the best sort of dog, day sixteen',
			 'date-published' => '1066-10-15',
			 'author' => $this->will,
			 'content' => implode("\n\n", [
				'It\'s pug. Pug is the best dog.'
			])],
		]);
	}

	public function testModelIsFormedCorrectly() {
		$blog = $this->blog;

		$this->assertEquals(4, count($blog));
		$this->assertEquals($this->will, $blog[0]->author);
		$this->assertEquals($this->harold, $blog[1]->author);
		foreach($blog as $entry) {
			$this->assertInstanceOf('\Phrototype\Prototype', $entry);
		}
	}

	public function testCanAlterModelDynamically() {
		$blog = $this->blog;

		$blog[1]->title = 'a new title';

		$this->assertEquals('a new title', $blog[1]->title);
	}
}