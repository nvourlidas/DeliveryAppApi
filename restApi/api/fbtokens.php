<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../config/Database.php';
include_once '../models/notifications.php';

$database = new Database();
$db = $database->connect();

$not = new Notification($db);

$data = json_decode(file_get_contents("php://input"));

$not->token = $data -> token;
$not->utype = $data -> utype;
$not->userid = $data -> userid;
$not->region = $data -> region;


if($not->token != null){
    $result = $not -> fbtoken();
}else{
    echo "Empty";
}    
echo $result;

?>