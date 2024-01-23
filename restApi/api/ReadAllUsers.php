<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../config/Database.php';
    include_once '../models/users.php';


    $database = new Database();
    $db = $database->connect();

    $users = new User($db);

    $result = $users->readallusers();
   

    echo $result;
?>