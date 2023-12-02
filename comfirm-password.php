<?php

error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['forgorPass'] = null;

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
forgotpasswordID ,
email,
member_id,
comfirmPassword
FROM forgot_password
WHERE member_id = '" . $entry['member_id'] . "' ";

$result = $conn->query($sql);

if (!$result) {
    $apiResponse['status'] = "error";
    $apiResponse['message'] = "wrong sql command";
    echo json_encode($apiResponse);
    exit;
}
$forgorPass = $result->fetch_assoc();
if ($forgorPass == null) {
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "data not found";
    echo json_encode($apiResponse);
    exit;
}
$apiResponse['forgorPass'] = $forgorPass;



echo json_encode($apiResponse);
exit;
?>