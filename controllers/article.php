<?php

$geo = $_GET["geo"];

// var_dump($geo);
// var_dump($_REQUEST);

// $client = new GuzzleHttp\Client(['base_uri' => 'https://news.google.com/news/rss/local/section/geo/']);
// $response = $client->request('GET', $geo . '?ned=us&gl=GB&hl=en');


// $response = simplexml_load_file('https://news.google.com/news/rss/local/section/geo/' . $geo . '?ned=us&gl=GB&hl=en');

$feed = implode(file('https://news.google.com/news/rss/local/section/geo/' . $geo . '?ned=us&gl=GB&hl=en'));
$xml = simplexml_load_string($feed);
$json = json_encode($xml);
$array = json_decode($json,TRUE);

// var_dump($array["channel"]["item"]);
// $res = $client->request('GET', 'https://api.github.com/user',);

// $geo = $_GET['geo'];

// $results = $database->selectAll('places');

// $filteredResults = array_filter($results, function ($result) {
//     return $result->admin_name1 == 'Wyoming';
// });
// https://news.google.com/news/rss/local/section/geo/64641?ned=us&gl=GB&hl=en

// var_dump($geo);

header('Content-Type: application/json');
if( array_key_exists("item", $array["channel"] )) {
    echo json_encode($array["channel"]["item"]);
} else {
    echo json_encode("No articles found");
}
