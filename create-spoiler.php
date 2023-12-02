<?php

error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['spoiler'] = null;

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

// 3. รับคำสั่งจาก ionic
$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);
// echo '<pre>';
// print_r($entry);

// up img
// สร้างตัวแปรมารับ
$profilepic = $entry['spoiler_img'];

// สร้าง
$imageName = uniqid();
$imageUrl = "";

try{
    
    $profilepicNothavePrefix = substr($profilepic, 5);
    $splited = explode(",", $profilepicNothavePrefix);
    $mimeType = $splited[0];
    $imageData = $splited[1];

    $splitedMimeType = explode(';', $mimeType, 2);
    $imageMimeType = $splitedMimeType[0];

    $splitedImageMimeType = explode('/', $imageMimeType, 2);
    $fileExtension = $splitedImageMimeType[1];
    $imageName = $imageName . "." . $fileExtension;
    $imagePath = "create-spoiler/" . $imageName;
    file_put_contents($imagePath, base64_decode($imageData));
    
    $imageUrl = "http://localhost/csc2023/" . $imagePath;

    // echo $imageName;
    // exit;

}catch (\Throwable $th){
 
}

// 4.  เขียนคำสั่ง SQL insert ข้อมูล
$sql = "
INSERT INTO spoiler (
    spoiler_name,
    spoiler_category,
    spoiler_details,
    spoiler_img,
    actor,
    director,
    created_at,
    member_id
  
)
VALUES (
 '" . $entry['spoiler_name'] . "',
 '" . $entry['spoiler_category'] . "',
 '" . $entry['spoiler_details'] . "',
 '" . $imageUrl . "',
 '" . $entry['actor'] . "',
 '" . $entry['director'] . "',
    NOW(),
 '" . $entry['member_id'] . "'
 )
";

// echo $sql;
// exit;

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