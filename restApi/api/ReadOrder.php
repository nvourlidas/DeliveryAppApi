<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../config/Database.php';
    include_once '../models/orders.php';


    $database = new Database();
    $db = $database->connect();

    $order = new Order($db);

    $result = $order->readorder();
   

    echo $result;
?>