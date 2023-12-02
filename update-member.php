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

    // echo json_encode($apiResponse);
    // exit;
}


// รับคำสั่งจาก ionic
$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);

// up img
// สร้างตัวแปรมารับ
$profilepic = $entry['profile_pic'];

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
    $imagePath = "uploads/" . $imageName;
    file_put_contents($imagePath, base64_decode($imageData));
    
    $imageUrl = "http://localhost/csc2023/" . $imagePath;

    // echo $imageName;
    // exit;

}catch (\Throwable $th){
 
}



$sql = "
UPDATE member
SET username =  '" . $entry['username'] . "', 
    fname = '" . $entry['fname'] . "', 
    lname = '" . $entry['lname'] . "', 
    email = '" . $entry['email'] . "', 
    password = '" . $entry['password'] . "',
    profile_pic = '" . $imageUrl . "'
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