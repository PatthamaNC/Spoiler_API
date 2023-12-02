<?php

error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");


$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['member'] = null;

// 1. connect ไปยัง database ว่าสำเร็จหรือไม่
$connection = mysqli_connect("localhost", "root", "", "the_movies");
mysqli_query($connection, 'set names utf8');

if (!$connection){
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "can not connet database" ;
    // echo "can not connect database";
    echo json_encode($apiResponse);
    exit;
}
$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);
// echo "connect database";

$sql = "
INSERT INTO forgot_password(
    email,
    member_id
    )
    VALUES(
        '" . $entry['email'] . "',
        '" . $entry['member_id'] . "'
        )
";
// echo $sql;

// 5. run SQL command
if ($connection->query ($sql) === false ){ //ถ้่า run ไม่สำเร็จ
    $connection->close();
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "wrong sql command" ;
    echo json_encode($apiResponse);
    // echo "wong sql insert command";
    exit;

}
echo json_encode($apiResponse);
exit;


?>