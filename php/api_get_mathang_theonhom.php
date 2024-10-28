<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Authorization');
header('Access-Control-Max-Age: 86400');
header('Content-Type: application/json');

if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
    exit();
}

require_once("server.php");

$manhommh = isset($_GET["MANHOMMH"]) ? $_GET["MANHOMMH"] : null;
$vt = isset($_GET["vt"]) ? $_GET["vt"] : null;

if (!$manhommh || !is_string($manhommh)) {
    echo json_encode(array('error' => 'Invalid MANHOMMH value'));
    exit();
}

$sql = "SELECT * FROM `mathang`, `nhommathang` WHERE mathang.MANHOMMH = nhommathang.MANHOMMH AND mathang.MANHOMMH = '$manhommh'";
$rs = mysqli_query($conn, $sql);

if (!$rs) {
    echo json_encode(array('error' => mysqli_error($conn)));
    exit();
}

$mang = array();
while ($rows = mysqli_fetch_array($rs)) {
    $usertemp = array();
    $usertemp['MAMH'] = $rows["MAMH"];
    $usertemp['MANHOMMH'] = $rows["MANHOMMH"];
    $usertemp['TENMH'] = $rows["TENMH"];
    $usertemp['TENNHANHANG'] = $rows["TENNHANHANG"];
    $usertemp['MOTA'] = $rows["MOTA"];
    $usertemp['XUATXU'] = $rows["XUATXU"];
    $usertemp['GIA'] = $rows["GIA"];
    $usertemp['ĐVT'] = $rows["ĐVT"];
    $usertemp['HINHANH'] = base64_encode($rows["HINHANH"]);
    $usertemp['vt'] = $vt;
    array_push($mang, $usertemp);
}

$jsondata['manhommh'] = $mang;
echo json_encode($jsondata);
mysqli_close($conn);
