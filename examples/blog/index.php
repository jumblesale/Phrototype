<?php

namespace Blog;

use \Phrototype\Model;

require(__DIR__ . '/../../vendor/autoload.php');

$app = new \Phrototype\App(
	['root' => 'examples/blog/']
);

echo $app->renderer()
	->method('mustache')
	->template(
		$app->read('views/template.mustache'),
		['css' => $app->import([
				 'pure',
				['blog-layout' => 'http://purecss.io/combo/1.3.10?/css/layouts/blog.css',]
		]),
	])
	->render(
		$app->read('views/post.mustache'),
		['posts' => Model\Factory::load('examples/blog/data/posts.json')]
	);