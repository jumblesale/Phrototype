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

$post = new \Phrototype\Validator();
	$post->form()->method('post')->action('/posts/add')
		->attributes(['class' => 'pure-form pure-form-stacked'])
		->submit('Post!', ['class' => 'pure-button pure-button-primary']);
	$post->group('post', 'Post')->field('title')
		->description('Post title');
	$post->group('post')->field('content')->type('text')
		->description('Post details');
	$post->group('author', 'Author')->field('name')
		->description('Author name');
	$post->group('author')->field('href')
		->description('Website');

$app->router()->get('/posts', function() use($app) {
	return $app->render(
		$app->read('views/post.mustache'),
		['posts' => Model\Factory::load('examples/blog/data/posts.json')->models()]
	);
});

$app->router()->get('/posts/add', function() use($app, $post) {
	return $app->render(
		'{{{form}}}',
		['form' => $post->html()]
	);
});

$app->router()->post('/posts/add', function() use($app, $post) {
	return $app->render(
		'<h2>data:</h2><pre>{{data}}</pre>',
		['data' => print_r($_POST, true)]
	);
});

echo $app->go();