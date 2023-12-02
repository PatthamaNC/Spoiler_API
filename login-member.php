<?php

error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['member'] = null;

//1.เชื่อมต่อ database
$conn = mysqli_connect("localhost", "root", "", "the_movies");

try {
    mysqli_query($conn, 'set names utf8');

    if (!$conn) {
        $apiResponse['status'] = "error";
        $apiResponse['message'] = "can not connect database";
        // echo "can not connect database";
        echo json_encode($apiResponse);
        exit;
    }
} catch (\Throwable $th) {
    $apiResponse['status'] = "error";
    $apiResponse['message'] = "can not connect database";
    echo json_encode($apiResponse);
    exit;
}
$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);
// echo "connect database";

$sql = "
SELECT 
id,
username,
fname,
lname,
email,
password,
profile_pic,
status
FROM member
WHERE email = '" . $entry['email'] . "' AND password = '" . $entry['password'] . "' 

";

$result = $conn->query($sql);

if (!$result) {
    $apiResponse['status'] = "error";
    $apiResponse['message'] = "wrong sql command";
    echo json_encode($apiResponse);
    exit;
}
$member = $result->fetch_assoc();
if ($member == null) {
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "data not found";
    echo json_encode($apiResponse);
    exit;
}
$apiResponse['memberstatus'] = $member;



echo json_encode($apiResponse);
exit;
?>
