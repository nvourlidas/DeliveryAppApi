<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../config/Database.php';
    include_once '../models/orders.php';

    

    $database = new Database();
    $db = $database->connect();

    $order = new Order($db);


    $data = json_decode(file_get_contents("php://input"));

    $order->orderid = $data->orderid;
    $order->userid = $data->userid;
    $order->state = $data->state;
    


    if($order->update()) {
        echo json_encode(
          array('message' => 'Post Updated')
        );
      } else {
        echo json_encode(
          array('message' => 'Post Not Updated')
        );
    }
?>