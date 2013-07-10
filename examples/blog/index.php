<?php

use Phrototype\Model;
use Phrototype\Validator;
use Phrototype\Renderer;
use Phrototype\Writer;

require(__DIR__ . '/../../vendor/autoload.php');

$app = new \Phrototype\App();

$viewReader = new Writer('examples/blog/views');

// $posts = Model::load('data/posts.json');

$tpl = $viewReader->read('template.mustache');

echo $tpl;