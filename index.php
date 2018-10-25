<?php

require 'vendor/autoload.php';  // when adding new classes to project make sure to rerun php composer.phar dump-autoload
$database = require 'core/bootstrap.php';

$router = new Router;


$uri = trim($_SERVER['REQUEST_URI'], '/');


require Router::load('routes.php')
    ->direct(Request::uri(), Request::method());