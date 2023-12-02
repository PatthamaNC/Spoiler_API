<?php

// error_reporting(E_ERROR | E_PARSE);
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
// $apiResponse['spoilersss'] = null;

$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);
$searchKey = $entry['searchKey'];

// HOWTO :: php connect mysql database
// 1. connect ไปยัง database
$connection = mysqli_connect("localhost", "root", "", "the_movies");
mysqli_query($connection, 'set names utf8');

// 2. check connection database ว่าสำเร็จหรือไม่
if (!$connection) {
  $apiResponse['status'] = 'error';
  $apiResponse['message'] = "can not connect database";
  // echo "can not connect database";
  echo json_encode($apiResponse);
  exit;
}



// 3. เขียนคำสั่ง SQL
if ($searchKey == '') {
  $apiResponse['spoiler'] = [];
  echo json_encode($apiResponse);
  exit;
}
  
$sql = "
SELECT * FROM `spoiler`
WHERE spoiler_name  LIKE '%". $searchKey . "%' 
OR actor  LIKE '%". $searchKey . "%' 
OR director  LIKE '%". $searchKey . "%' 
";

$sql2 = "
SELECT * FROM `videos`
WHERE name  LIKE '%". $searchKey . "%' 
OR actor  LIKE '%". $searchKey . "%' 
OR director  LIKE '%". $searchKey . "%' 
";


// 4. run คำสั่ง sql

$result = $connection->query($sql);

// 5. check run คำสั่ง sql error
if (!$result) {
  $apiResponse['status'] = 'error';
  $apiResponse['message'] = "wrong sql command";
  echo json_encode($apiResponse);
  exit;
}

// 6. ดึงข้อมูลออกมาแสดง
// $member = $result->fetch_assoc();
$spoiler = $result->fetch_all(MYSQLI_ASSOC);
$apiResponse['spoiler'] = $spoiler;


$result2 = $connection->query($sql2);

// 5. check run คำสั่ง sql error
if (!$result2) {
  $apiResponse['status'] = 'error';
  $apiResponse['message'] = "wrong sql command";
  echo json_encode($apiResponse);
  exit;
}

// 6. ดึงข้อมูลออกมาแสดง
// $member = $result->fetch_assoc();
$video = $result2->fetch_all(MYSQLI_ASSOC);
$apiResponse['video'] = $video;

echo json_encode($apiResponse);
exit;

?>