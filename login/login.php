<?php
include_once './config/database.php';
require "./vendor/autoload.php";
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With,  WWW-Authenticate");


$username = '';
$password = '';


$database = new Database();
$db = $database->connect();




$data = json_decode(file_get_contents("php://input"));

$username = $data->username;
$password = $data->password;    


$query="SELECT * FROM users ";
$stmt = $db->prepare($query);
$stmt->execute();

while($row = $stmt->fetch()) {
    if($username==$row['username'] && $password==$row['password']){
    $userid = $row['userid'];
    $name = $row['name'];
    $surname = $row['surname'];
    $usertype = $row['usertype'];
    $region = $row['region'];
       
    
        $secret_key = "nikos123456789";
        $issuedat_claim = time(); // issued at
        $notbefore_claim = $issuedat_claim ; //not before in seconds
        $expire_claim = $issuedat_claim + (36000); // expire time in seconds
        $token = array(
            "iat" => $issuedat_claim,
            "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "data" => array(
                "userid" => $userid,
                "name" => $name,
                "surname" => $surname,
                "utype" => $usertype,
                "region" => $region,       
 ));

        http_response_code(200);

        $jwt = JWT::encode($token, $secret_key);
        echo json_encode(
            array(
                "res" => 1,
                "message" => "Successful login.",
                "jwt" => $jwt,
                "expireAt" => $expire_claim,
                'userid'=>$userid
            ));
            
        

    }
    
}

?>