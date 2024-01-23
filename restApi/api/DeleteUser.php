<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../config/Database.php';
    include_once '../models/users.php';



    $database = new Database();
    $db = $database->connect();

    $user = new User($db);


    $user->userid = isset($_GET['userid']) ? $_GET['userid'] : die();
    
    
    if($user->deleteuser()) {
        echo json_encode(
          array('message' => 'Post Deleted')
        );
      } else {
        echo json_encode(
          array('message' => 'Post Not Deleted')
        );
    }
    echo $user->userid;
?>