<?php

use Phrototype\Model;
use Phrototype\Validator;
use Phrototype\Renderer;
use Phrototype\Writer;

require(__DIR__ . '/../../vendor/autoload.php');

$app = new \Phrototype\App();

$viewReader = new Writer('examples/blog/views/');

$tpl = $viewReader->read('template.mustache');

echo $app->renderer()
	->method('mustache')
	->template($tpl, [
		'css' => 
			$app->import([
				 'pure',
				['blog-layout' => 'http://purecss.io/combo/1.3.10?/css/layouts/blog.css',]
			]),
	])
	->render(
		$viewReader->read('post.mustache'),
		['posts' => Model\Factory::load('examples/blog/data/posts.json')]
	);