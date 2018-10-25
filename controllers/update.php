<?php

// $geo = $_POST['parameters']; 
// var_dump($_POST);



$ne = explode(',', $_POST["ne"]);
$sw = explode(',', $_POST["sw"]);
// put in error handling that checks if $geo are ok 

$results = $database->selectByGeo('places', $ne, $sw);

// $filteredResults = array_filter($results, function ($result) {
//     return $result->admin_name1 == 'Wyoming';
// });
// https://news.google.com/news/rss/local/section/geo/64641?ned=us&gl=GB&hl=en

// var_dump($results);

header('Content-Type: application/json');
echo json_encode($results);