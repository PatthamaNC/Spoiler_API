<?php

error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = 'update comment success';

// 1. connect ไปยัง database ว่าสำเร็จหรือไม่
$connection = mysqli_connect("localhost", "root", "", "the_movies");
mysqli_query($connection, 'set names utf8');

if  (!$connection){
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "can not connet database";
}

// รับคำสั่งจาก ionic
$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);


$sql = "
UPDATE comment_spoiler
    SET member_id =  '" . $entry['member_id'] . "', 
    movie_id = '" . $entry['movie_id'] . "', 
    message = '" . $entry['message'] . "'
WHERE 
    commentID   = '" . $entry['commentID'] . "'
";

// echo $sql;
// exit;

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