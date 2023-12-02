<?php

// error_reporting(E_ERROR | E_PARSE);
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['comments'] = null;

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

$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);

// 3. เขียนคำสั่ง SQL
$sql = "
SELECT 
  cs.commentID,
  cs.member_id,
  cs.movie_id,
  cs.message,
  cs.created_at,
  m.username,
  m.profile_pic
FROM comment_spoiler AS cs

LEFT JOIN member AS m 
  ON cs.member_id = m.id
  WHERE cs.movie_id  = " . $entry['MovieID'] . "
  ORDER BY cs.commentID DESC
";
// echo ($sql);
// exit();

// 4. run คำสั่ง sql
$result = $connection->query($sql);

// 5. check run คำสั่ง sql error
if (!$result) {
  $apiResponse['status'] = 'error';
  $apiResponse['message'] = "wrong sql command";
  echo json_encode($apiResponse);
  exit;
}

// echo "run sql command success";

// 6. ดึงข้อมูลออกมาแสดง
// $member = $result->fetch_assoc();
$comments = $result->fetch_all(MYSQLI_ASSOC);
$apiResponse['comments'] = $comments;

echo json_encode($apiResponse);
exit;

?>