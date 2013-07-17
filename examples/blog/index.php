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
		[
			'css' => $app->import([
				 'pure',
				['blog-layout' => 'http://purecss.io/combo/1.3.10?/css/layouts/blog.css',]
			]),
			'title' => 'Phrototype Blog'
		]);

$post = new \Phrototype\Validator();
	$post->form()->method('post')
		->action('/posts/add')
		->attributes(['class' => 'pure-form pure-form-stacked'])
		->submit('Post!', ['class' => 'pure-button pure-button-primary']);
	$post->group('post', 'Post');
	$post
		->field('title')
		->description('Post title');
	$post
		->field('content', 'text')
		->description('Post details');
	$post->group('author', 'Author');
	$post
		->field('author[name]')
		->description('Author name');
	$post
		->field('author[href]')
		->description('Website');

$app->router()->get('/posts', function() use($app) {
	return $app->render(
		$app->read('views/post.mustache'),
		['posts' => Model\Factory::load('examples/blog/data/posts.json')
			->reverse()->models()]
	);
});

$app->router()->get('/posts/add', function() use($app, $post) {
	$app->renderer()->template()->title = 'Add a new post';
	return $app->render(
		'{{{form}}}',
		['form' => $post->html()]
	);
});

$app->router()->post('/posts/add', function() use($app, $post) {
	if($post->validate($app->request()->post())) {
		$model = Model::forge($post->data());
		Model\Factory::load('examples/blog/data/posts.json')
			->add($model)
			->save('examples/blog/data/posts.json');
			
			return $app->router()->dispatch('get', '/posts');
	} else {
		return $app->render(
			'<pre>{{errors}}</pre>{{{form}}}',
			['form' => $post->html(), 'errors' => print_r($post->messages(), 1)]
		);
	}
});

echo $app->go();