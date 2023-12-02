<?php
error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['member'] = null; 

// 1. connect ไปยัง database ว่าสำเร็จหรือไม่
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

// }
// mysqli_query($connection, 'set names utf8');
// if (!$connection){
//     $apiResponse = ['status'] = 'error';
//     $apiResponse = ['message'] = "can not connet database" ;
//     echo json_encode($apiResponse);
//     exit;
// }

//     echo "connect database success";

$sql = "
SELECT 
id,
username,
fname,
lname,
email,
password
FROM member
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

$member = $result->fetch_all(MYSQLI_ASSOC);
$apiResponse['member'] = $member;

echo json_encode($apiResponse);
exit;

// if(!$result){
//     $apiResponse['status'] = 'error';
//     $apiResponse['mess']
// }

// echo "run sql commend success";

// echo ดึงข้อมูลออกมาแสดง
// $member = $result->fetch_all(MYSQLI_ASSOC);
// // echo "<pre>";
// // print_r($member);
// foreach ($member as $member){
//     echo "<pre>";
//     print_r($member);
// }

?>