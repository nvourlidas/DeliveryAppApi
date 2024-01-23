<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../config/Database.php';
    include_once '../models/notifications.php';


    $database = new Database();
    $db = $database->connect();

    $not = new Notification($db);

    $result = $not->readtokens();
   

    echo $result;
?>