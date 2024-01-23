<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../config/Database.php';
    include_once '../models/users.php';

    

    $database = new Database();
    $db = $database->connect();

    $users = new User($db);

    $data = json_decode(file_get_contents("php://input"));

    $users->userid = $data->userid;
    $users->username = $data->username;
    $users->password = $data->password;
    $users->name = $data->name;
    $users->surname = $data->surname;

    if($users->update()) {
        echo json_encode(
          array('message' => 'Post Updated')
        );
      } else {
        echo json_encode(
          array('message' => 'Post Not Updated')
        );
    }

?>