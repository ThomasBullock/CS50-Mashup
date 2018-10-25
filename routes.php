<?php

$router->get('', 'controllers/index.php');
$router->get('map', 'controllers/map.php');
$router->get('about', 'controllers/about.php');
$router->get('articles', 'controllers/article.php');
$router->post('update', 'controllers/update.php');
$router->post('search', 'controllers/search.php');
