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


    
    $users->username = $data->username;
    $users->password = $data->password;
    $users->name = $data->name;
    $users->surname = $data->surname;
    $users->usertype = $data->usertype;
    $users->region = $data->region;

    echo $users->name;
    
        
        
        if($users->username != null){
            $result = $users->createUser();
        }else{
            echo "Empty";
        }   
        
    
    echo $result;
    
?>