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

//2. check connection database ว่าสำเร็จหรือไม่
if (!$connection){
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "can not connet database" ;
    // echo "can not connect database";
    echo json_encode($apiResponse);
    exit;
}

// รับคำสั่งจาก ionic
$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);

$sql = "
SELECT
    id,
    username,
    fname,
    lname,
    email,
    password,
    profile_pic
FROM member
WHERE id  = '" . $entry['member_id'] . "'
";
echo ($sql);
exit();

$result = $connection-> query ($sql);

   if(!$result) {
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "wrong sql command" ;
    echo json_encode($apiResponse);
    exit;
}

// ดึงข้อมูลออกมาแสดง
$member = $result->fetch_assoc();
$apiResponse['member'] = $member;

echo json_encode($apiResponse);
exit;

?>