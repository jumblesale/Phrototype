<?php

namespace Blog;

use \Phrototype\Model;

require(__DIR__ . '/../../vendor/autoload.php');

$app = new \Phrototype\App(
	['root' => 'examples/blog/'
	,'defaultRenderer' => 'mustache']
);

$app->renderer()
	->method('mustache')
	->template(
		$app->read('views/template.mustache'),
		['css' => $app->import([
				 'pure',
				['blog-layout' => 'http://purecss.io/combo/1.3.10?/css/layouts/blog.css',]
		]),
	]);

$app->router()->get('/posts', function() use($app) {
	return $app->render(
		$app->read('views/post.mustache'),
		['posts' => Model\Factory::load('examples/blog/data/posts.json')]
	);
});

echo $app->go();