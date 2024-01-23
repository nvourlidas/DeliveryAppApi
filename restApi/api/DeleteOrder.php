<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../config/Database.php';
    include_once '../models/orders.php';



    $database = new Database();
    $db = $database->connect();

    $order = new Order($db);


    $order->orderid = isset($_GET['orderid']) ? $_GET['orderid'] : die();
    
    
    if($order->deleteorder()) {
        echo json_encode(
          array('message' => 'Post Deleted')
        );
      } else {
        echo json_encode(
          array('message' => 'Post Not Deleted')
        );
    }
    echo $order->orderid;
?>