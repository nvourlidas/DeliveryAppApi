<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../config/Database.php';
    include_once '../models/notifications.php';


    $database = new Database();
    $db = $database->connect();

    $order = new Notification($db);

    $result = $order->notifications();
   
    
    echo $result;

?>