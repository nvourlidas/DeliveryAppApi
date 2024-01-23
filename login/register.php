<?php
include_once './config/database.php';


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With,  WWW-Authenticate");


$database = new Database();
$db = $database->connect();

$data = json_decode(file_get_contents("php://input"));


$id = $data -> id;
$password=$data->password;

$hashed=password_hash($password, PASSWORD_DEFAULT);

$query="UPDATE users SET password = :hashe  WHERE userid = $id";

        $stmt =$db->prepare($query);
        $stmt->bindParam(':hashe', $hashed);

        $stmt->execute();


echo $password;
echo $hashed;


?>