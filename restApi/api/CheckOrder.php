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
$not -> userid = $data -> userid;

$serverKey = 'AAAAlxTbnUU:APA91bHuOwmz_uu-UH1j4gVJlkngDblm1lMrQ2B6aAn12SHDgypQuYPi9UzYxfa_954LoN1xCGMnzORrpcjBYOPOUUARm-gsFWBpXC7K_NrKWL-4_fpP6L_-0i3SP1MHtmwr2k429iUr';

$token = $not-> singletoken();

$check = $not->checkmin();

$message = [
    'notification' => [
        'title' => 'Ανοιχτή Παραγγελία',
        'body' => 'Η Παραγελλία '. $check . ' είναι ανοιχτή πάνω από 20 λεπτά',
    ],
    'to' => $token
];

$options = [
    'http' => [
        'header' => "Authorization: key=$serverKey\r\n" .
                    "Content-Type: application/json\r\n",
        'method' => 'POST',
        'content' => json_encode($message),
    ],
];

$context = stream_context_create($options);

if($check != null) {
    $result = file_get_contents('https://fcm.googleapis.com/fcm/send', false, $context);
}

if ($result === false) {
    die('Request failed.');
}

echo $result;
echo $check;
?>