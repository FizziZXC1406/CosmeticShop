<?php

// Specify domains from which requests are allowed
header('Access-Control-Allow-Origin: *');

// Specify which request methods are allowed
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');

// Additional headers which may be sent along with the CORS request
header('Access-Control-Allow-Headers: X-Requested-With,Authorization,Content-Type');

// Set the age to 1 day to improve speed/caching.
header('Access-Control-Max-Age: 86400');

// Exit early so the page isn't fully loaded for options requests
if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
    exit();
}

require_once("server.php");

$sql_tuongtu = "SELECT MANHOMMH, TENMH, GIA, ĐVT, HINHANH FROM `mathang` WHERE MANHOMMH = 'ST1'";
$rs_tuongtu = mysqli_query($conn, $sql_tuongtu);
$mang_tuongtu = array();
while ($rows = mysqli_fetch_array($rs_tuongtu)) {
    $usertemp['MANHOMMH'] = $rows["MANHOMMH"];
    $usertemp['TENMH'] = $rows["TENMH"];
    $usertemp['GIA'] = $rows["GIA"];
    $usertemp['ĐVT'] = $rows["ĐVT"];
    $usertemp['HINHANH'] = base64_encode($rows["HINHANH"]);
    array_push($mang_tuongtu, $usertemp);
}
$jsondata['tuongtu'] = $mang_tuongtu;

echo json_encode($jsondata);
mysqli_close($conn);
