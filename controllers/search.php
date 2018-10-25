<?php

// $geo = $_POST['parameters']; 

$query = $_POST["query"];

// $ne = explode(',', $_POST["ne"]);
// $sw = explode(',', $_POST["sw"]);
// put in error handling that checks if $geo are ok 

$results = $database->searchByString('places', $query);



header('Content-Type: application/json');
echo json_encode($results);