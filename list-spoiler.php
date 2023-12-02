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

// 2. check connection database ว่าสำเร็จหรือไม่
if  (!$connection){
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "can not connet database";

    echo json_encode($apiResponse);
    exit;
}


// รับคำสั่งจาก ionic
$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);

$sql = "
SELECT spoiler
SET spoilerID =  '" . $entry['spoilerID'] . "', 
spoiler_name = '" . $entry['spoiler_name'] . "', 
spoiler_category = '" . $entry['spoiler_category'] . "', 
spoiler_details = '" . $entry['spoiler_details'] . "', 
spoiler_img = '" . $entry['spoiler_img'] . "',
    actor = '" . $entry['actor'] . "', 
    director = '" . $entry['director'] . "',
    created_at = '" . $entry['created_at'] . "'


    WHERE 
    spoilerID  = '" . $entry['movie_id'] . "'
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