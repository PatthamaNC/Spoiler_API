<?php

error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = 'create ReportSpoiler success';

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

// 3. รับค่าจาก ionic
$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);

if (
  $entry['member_id'] == null || $entry['member_id'] == ""
  || $entry['spoiler_id'] == null || $entry['spoiler_id'] == ""
  || $entry['reason'] == null || $entry['reason'] == ""
) {
  $apiResponse['status'] = 'error';
  $apiResponse['message'] = "invalid parameter";
  echo json_encode($apiResponse);
  exit;
}
  
// 4. เขียนคำสั่ง SQL insert ข้อมูล
$sql = "
INSERT INTO report_spoiler (
  member_id,
  spoiler_id,
  reason,
  status,
  created_at
)
VALUES (
  '" . $entry['member_id'] . "',
  '" . $entry['spoiler_id'] . "',
  '" . $entry['reason'] . "',
  '" . $entry['status'] . "',
  NOW()
)
";

// 5. run SQL command
if ($connection->query($sql) === false) { // ถ้า run ไม่สำเร็จ
  $connection->close();
  $apiResponse['status'] = 'error';
  $apiResponse['message'] = "wrong sql command";
  echo json_encode($apiResponse);
  exit;
}

echo json_encode($apiResponse);
exit;

?>