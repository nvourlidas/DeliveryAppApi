<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../config/Database.php';
    include_once '../models/orders.php';

    date_default_timezone_set('Africa/Nairobi');

    $database = new Database();
    $db = $database->connect();

    $order = new Order($db);


    $data = json_decode(file_get_contents("php://input"));


    $order->address = $data->address;
    $order->price = $data->price;
    $order->texta = $data->texta;
    $order->userid = $data->userid;
    $order->region = $data->region;
    $order->odate = date('Y-m-d');
    $order->otime = date("h:i:sa");

    if($order->address != null){
        $result = $order->createorder();
    }else{
        echo "Empty";
    }    
    echo $result;
    
?>