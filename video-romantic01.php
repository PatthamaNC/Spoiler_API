<?php

// 
error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['videoromantic'] = null; 

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


$sql = "
SELECT 
videoromanticID ,
romantic_name,
romantic_category,
romantic_details,
actor,
director,
video_romantic,
romantic_img
FROM videoromantic
";

// run คำสั่ง sql
$result = $connection-> query ($sql);
if (!$result){
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "wrong mysql command" ;
    echo json_encode($apiResponse);
    // echo "wrong aql command";
    exit;
}

$videoromantic = $result->fetch_all(MYSQLI_ASSOC);
$apiResponse['videoromantic'] = $videoromantic;

echo json_encode($apiResponse);
exit;


?>