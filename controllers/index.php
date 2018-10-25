<?php

$results = $database->selectAll('places');

$filteredResults = array_filter($results, function ($result) {
    return $result->admin_name1 == 'Wyoming';
});

// $modified = array_map(function($result) {
//     $result->place_name = $result->place_name . '!';
//     return $result;

//     // return ['title' => $result->place_name];
// }, $results); // note reversed from filter!!

// var_dump($modified);

// $names = array_column($results, 'place_name');  // returns only the designated column! must be public

require 'views/index.view.php';