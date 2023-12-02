<?php

// 
error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;


// 1. connect ไปยัง database ว่าสำเร็จหรือไม่
$connection = mysqli_connect("localhost", "root", "", "the_movies");

try{
    mysqli_query($connection, 'set names utf8');

    // 2. check connection database ว่าสำเร็จหรือไม่
if (!$connection){
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "can not connet database" ;
    echo json_encode($apiResponse);
    exit;

}
} catch (\Throwable $th){
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "can not connet database" ;
    echo json_encode($apiResponse);
    exit;
}
// รับคำสั่งจาก ionic
$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);

// 3. เขียนคำสั่ง SQL
$sql = "
SELECT 
    spoilerID,
    spoiler_name,
    spoiler_category,
    spoiler_details,
    spoiler_img,
    actor,
    director,
    created_at
FROM spoiler

WHERE spoilerID =  '" . $entry['MyMoviesEdit'] . "'

";

// 4. run คำสั่ง sql
$result = $connection-> query ($sql);
// 5. check run คำสั่ง sql error
if (!$result){
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "wrong mysql command" ;
    echo json_encode($apiResponse);
    exit;
}

// 6. ดึงข้อมูลออกมาแสดง
$mymovie = $result->fetch_assoc();
$apiResponse['mymovie'] = $mymovie;

echo json_encode($apiResponse);
exit;


?>