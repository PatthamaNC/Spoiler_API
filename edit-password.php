<?php

error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = 'update member success';

// 1. connect ไปยัง database ว่าสำเร็จหรือไม่
$connection = mysqli_connect("localhost", "root", "", "the_movies");
mysqli_query($connection, 'set names utf8');

if  (!$connection){
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "can not connet database";
}
// รับคำสั่งจาก ionic
$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);
$sql = "
UPDATE member
SET  
    password = '" . $entry['password'] . "'
WHERE 
    id  = '" . $entry['id'] . "'
";
if ($connection->query ($sql) === false ){
    $connection->close();
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "wrong sql command" ;
    echo json_encode($apiResponse);
    echo "wong sql insert command";
    exit;
}
// echo "update data success";
echo json_encode($apiResponse);
exit;
?>