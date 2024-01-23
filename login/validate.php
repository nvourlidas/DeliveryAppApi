<?php


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
require "./vendor/autoload.php";
use \Firebase\JWT\JWT;




$data = json_decode(file_get_contents("php://input"));
 
$key = 'nikos123456789';

$jwt=isset($data->jwt) ? $data->jwt : "";

if($jwt){
 
   
    try {
        
        $decoded = JWT::decode($jwt, $key, ['HS256']);
 
        
        http_response_code(200);
 
       
        echo json_encode(array(
            "message" => "Access granted.",
            "user" => $decoded->data
        ));
 
    }
 
    catch (Exception $e){
 
        
     
        
        echo json_encode(array(
            "res" => 1,
            "error" => $e->getMessage()
        ));
    }
}else{
    echo json_encode(array("res" => 1));
}
?>