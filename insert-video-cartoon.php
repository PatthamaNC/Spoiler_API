<?php

error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['video'] = null;

// 1. connect ไปยัง database ว่าสำเร็จหรือไม่
$connection = mysqli_connect("localhost", "root", "", "the_movies");
mysqli_query($connection, 'set names utf8');

// 2. check connection database ว่าสำเร็จหรือไม่
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
$videoPic = $entry['videoimg'];
$video = $entry['video'];


// สร้าง
$imageName = uniqid();
$imageUrl = "";
$videoName = uniqid();
$videoUrl = "";

// videoimg
try{
    
    $profilepicNothavePrefix = substr($videoPic, 5);
    $splited = explode(",", $profilepicNothavePrefix);
    $mimeType = $splited[0];
    $imageData = $splited[1];

    $splitedMimeType = explode(';', $mimeType, 2);
    $imageMimeType = $splitedMimeType[0];

    $splitedImageMimeType = explode('/', $imageMimeType, 2);
    $fileExtension = $splitedImageMimeType[1];
    $imageName = $imageName . "." . $fileExtension;
    $imagePath = "uploads/videospoiler/videoimg/" . $imageName;
    file_put_contents($imagePath, base64_decode($imageData));
    
    $imageUrl = "http://localhost/csc2023/" . $imagePath;

    // echo $imageName;
    // exit;

}catch (\Throwable $th){
 
}

try{
    
    $VideoNothavePrefix = substr($video, 5);
    $splited = explode(",", $VideoNothavePrefix);
    $mimeType = $splited[0];
    $videoData = $splited[1];

    $splitedMimeType = explode(';', $mimeType, 2);
    $videoMimeType = $splitedMimeType[0];

    $splitedMimeType = explode('/', $videoMimeType, 2);
    $fileExtension = $splitedMimeType[1];

    $videoName = $videoName . "." . $fileExtension;
    $videoPath = "uploads/videospoiler/video/" . $videoName;
    file_put_contents($videoPath, base64_decode($videoData));
    
    $videoUrl = "http://localhost/csc2023/" . $videoPath;

    // echo $imageName;
    // exit;

}catch (\Throwable $th){
 
}


// 4.  เขียนคำสั่ง SQL insert ข้อมูล
$sql = "
INSERT INTO video (
    video_name,
    video_genra,
    video_subtitles,
    actor,
    director,
    videocartoon,
    videoimg
)
VALUES (
 '" . $entry['video_name'] . "',
 '" . $entry['video_genra'] . "',
 '" . $entry['video_subtitles'] . "',
 '" . $entry['actor'] . "',
 '" . $entry['director'] . "',
 '" . $videoUrl . "',
 '" . $imageUrl . "')

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