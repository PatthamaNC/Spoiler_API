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
    s.spoilerID  ,
    s.spoiler_name,
    s.spoiler_category,
    s.spoiler_details,
    s.spoiler_img,
    s.actor,
    s.director,
    s.created_at,
    s.member_id,
    m.username,
    m.profile_pic

FROM spoiler  AS s
    LEFT JOIN member AS m 
    ON s.member_id = m.id
WHERE spoilerID = '" . $entry['ListSpoilerID'] . "'
ORDER BY s.spoilerID DESC
";
// run คำสั่ง sql
$result = $connection-> query ($sql);
if (!$result){
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "wrong mysql command" ;
    echo json_encode($apiResponse);
    exit;
}

$listspoiler = $result->fetch_assoc();
$apiResponse['listspoiler'] = $listspoiler;

echo json_encode($apiResponse);
exit;


?>