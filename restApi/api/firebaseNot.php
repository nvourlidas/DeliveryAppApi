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
$not -> userid=$data-> userid;
$not -> sname= $data->name;
$not -> utype= $data->utype;
$not -> region= $data->region;
$orderid = $data -> orderid;

$serverKey = 'AAAAlxTbnUU:APA91bHuOwmz_uu-UH1j4gVJlkngDblm1lMrQ2B6aAn12SHDgypQuYPi9UzYxfa_954LoN1xCGMnzORrpcjBYOPOUUARm-gsFWBpXC7K_NrKWL-4_fpP6L_-0i3SP1MHtmwr2k429iUr'; // Your FCM server key
$tokens = $not -> tokens();

if($not->utype == 2){
    $message = [
        'notification' => [
            'title' => 'Ολοκλήρωση Παραγγελίας',
            'body' => 'Αριθμός Παραγγελίας: '. $orderid,
        ],
        'registration_ids' => $tokens
    ];
}else{
$user = $not -> shop();

$message = [
    'notification' => [
        'title' => 'Νέα Παραγγελία',
        'body' => 'Από Κατάστημα:'. $user,
    ],
    'registration_ids' => $tokens
];
}
$options = [
    'http' => [
        'header' => "Authorization: key=$serverKey\r\n" .
                    "Content-Type: application/json\r\n",
        'method' => 'POST',
        'content' => json_encode($message),
    ],
];

$context = stream_context_create($options);


$result = file_get_contents('https://fcm.googleapis.com/fcm/send', false, $context);


if ($result === false) {
    die('Request failed.');
}

echo $result;
echo json_encode($tokens);

?>