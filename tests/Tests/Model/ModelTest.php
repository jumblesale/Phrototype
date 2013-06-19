<?php

namespace Phrototype\Tests\Model;

use Phrototype\Model\Model;

class ModelTest extends \PHPUnit_Framework_TestCase {
	public function createBlog() {
		/*$blog = Model::create([
			'title'		=> [
				'type'		=> 'text',
				'constraints'	=> ['length'	=> '255',],
				'mutator'		=> function($title) {
					return ucwords($title);
				}
			],
			'date-published'	=> ['type' => 'date'],
			'author'	=> Model::create([
				'name'		=> ['type' => 'text',],
				'email'		=> ['type' => 'email',],
			]),
			'content'	=> ['type' => 'text', 'format' => 'markdown',]
		])->load([
			['title' => 'the hunt for the best sort of dog, day one',
			 'date-published' => '1066-09-29',
			 'author' => ['name' => 'Will', 'email' => 'will@norman.dy',],
			 'content' => implode("\n\n", [
			 	'I have arrived in England to find the best sort of dog.',
			 	'So far only light resistance.',
			 	'No dogs to report at this time.',
			])],
			['title' => 'that scoundrel will not get my dogs',
			 'date-published' => '1066-10-12',
			 'author' => ['name' => 'Harold', 'email' => 'harry@royal.gov.uk',],
			 'content' => implode("\n\n", [
			 	'That William fellow is trying to get at our dogs.',
			 	'I won\'t stand for it!',
			 	'Have at thee!',
			])],
			['title' => 'the hunt for the best sort of dog, day fourteen',
			 'date-published' => '1066-10-13',
			 'author' => ['name' => 'Will', 'email' => 'will@norman.dy',],
			 'content' => implode("\n\n", [
			 	'I\'ve narrowed my list down to to sorts of dogs:',
			 	"1.pug\n1.french bulldog",
			 	'Hang on, got to go and sort something out...'
			])],
			['title' => 'the hunt for the best sort of dog, day sixteen',
			 'date-published' => '1066-10-15',
			 'author' => ['name' => 'Will', 'email' => 'will@royal.gov.uk',],
			 'content' => implode("\n\n", [
			 	'It\'s pug. Pug is the best dog.'
			])],
		]);*/
		$blog = [];
		return $blog;
	}

	public function testShutUpPHPUnit() {
		$this->assertTrue(true);
	}

	/**
	 * @dataProvider createBlog
	 */
}