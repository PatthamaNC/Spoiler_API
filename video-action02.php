<?php

// 
error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;


// connect ไป database
$connection = mysqli_connect("localhost", "root", "", "the_movies");

try{
    mysqli_query($connection, 'set names utf8');

    // mysqli_query($connection, 'set names utf8');
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
$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);


$sql = "
SELECT 
videoactionID ,
action_name,
action_category,
action_details,
actor,
director,
video_action,
action_img
FROM videoaction
WHERE videoactionID = '" . $entry['VideoactionID'] . "'
";

// run คำสั่ง sql
$result = $connection-> query ($sql);
if (!$result){
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "wrong mysql command" ;
    echo json_encode($apiResponse);
    exit;
}

$videoaction = $result->fetch_assoc();
$apiResponse['videoaction'] = $videoaction;

echo json_encode($apiResponse);
exit;


?>