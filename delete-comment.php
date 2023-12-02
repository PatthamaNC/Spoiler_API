<?php
error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['comments'] = null;

// 1. connect ไปยัง database ว่าสำเร็จหรือไม่
$connection = mysqli_connect("localhost", "root", "", "the_movies");
mysqli_query($connection, 'set names utf8');

if (!$connection){
    echo "can not connect database";
    exit;
}

// 3. รับคำสั่งจาก ionic
$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);
// echo '<pre>';
// print_r($entry);

// 4. เขียนคำสั่ง SQL insert ข้อมูล
$sql = "
DELETE FROM comment_spoiler WHERE commentID   = '" . $entry['commentID'] . "'
";

// 5. run SQL command
if ($connection->query ($sql) === false ){ //ถ้า run ไม่สำเร็จ
    $connection->close();
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "wrong sql command" ;
    echo json_encode($apiResponse);
    // echo "wong sql insert command";
    exit;
}
// echo "delete data success";
echo json_encode($apiResponse);
exit;
?>