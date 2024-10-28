<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With,Authorization,Content-Type');
header('Access-Control-Max-Age: 86400');

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
