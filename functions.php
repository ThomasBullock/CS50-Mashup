<?php 


function dd($val) 
{
    echo '<pre>';
        die(var_dump($val));
    echo '</pre>';
};

function connectToDb() // moved to Connection class
{
    try {
        return $pdo =  new PDO('mysql:host=127.0.0.1;dbname=mashup', 'root', 'T@hb1lk');
    } catch (PDOException $e) {
        die('Could not connect to DB');
    }
}

function fetchAllPlaces($pdo) 
{
    $statement = $pdo->prepare('select * from places');
    $statement->execute();
    
    return $statement->fetchAll(PDO::FETCH_CLASS, 'Place'); // we want to fetch the results and put into class Place
}