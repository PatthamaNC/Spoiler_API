<?php

// 
error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['video'] = null; 

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
videoID,
video_name,
video_genra,
video_subtitles,
actor,
director,
videocartoon,
videoimg
FROM video
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

$video = $result->fetch_all(MYSQLI_ASSOC);
$apiResponse['video'] = $video;

echo json_encode($apiResponse);
exit;


?>