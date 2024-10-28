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

$mamh = isset($_GET["MAMH"]) ? $_GET["MAMH"] : null;
if (!$mamh || !is_string($mamh)) {
    echo json_encode(array('error' => 'Invalid MAMH value'));
    exit();
}

$sql = "SELECT * FROM `mathang`, `nhommathang` 
        WHERE mathang.MANHOMMH = nhommathang.MANHOMMH AND mathang.MAMH = '$mamh'";
$rs = mysqli_query($conn, $sql);
if (!$rs) {
    echo json_encode(array('error' => mysqli_error($conn)));
    exit();
}
$mang = array();
$manhommh = null;
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
    array_push($mang, $usertemp);
    $manhommh = $rows["MANHOMMH"];
}
if (!$manhommh) {
    echo json_encode(array('error' => 'Product not found'));
    exit();
}
$jsondata['mamathang'] = $mang;

$sql_maumathang = " SELECT maumathang.MAMH, maumathang.TENMAU, maumathang.HINHANHMAU FROM `maumathang`, `mathang` 
                    WHERE maumathang.MAMH = mathang.MAMH 
                    AND mathang.MAMH = '$mamh'";
$rs_maumathang = mysqli_query($conn, $sql_maumathang);
if (!$rs_maumathang) {
    echo json_encode(array('error' => mysqli_error($conn)));
    exit();
}
$mang_maumathang = array();
while ($rows = mysqli_fetch_array($rs_maumathang)) {
    $usertemp = array();
    $usertemp['MAMH'] = $rows["MAMH"];
    $usertemp['TENMAU'] = $rows["TENMAU"];
    $usertemp['HINHANHMAU'] = base64_encode($rows["HINHANHMAU"]);
    array_push($mang_maumathang, $usertemp);
}
if (!$manhommh) {
    echo json_encode(array('error' => 'Product not found'));
    exit();
}
$jsondata['maumathang'] = $mang_maumathang;


$sql_related = "SELECT * FROM `mathang` WHERE MANHOMMH = '$manhommh' AND MAMH != '$mamh'";
$rs_related = mysqli_query($conn, $sql_related);
if (!$rs_related) {
    echo json_encode(array('error' => mysqli_error($conn)));
    exit();
}
$mang_related = array();
while ($rows_related = mysqli_fetch_array($rs_related)) {
    $related = array();
    $related['MAMH'] = $rows_related["MAMH"];
    $related['MANHOMMH'] = $rows_related["MANHOMMH"];
    $related['TENMH'] = $rows_related["TENMH"];
    $related['GIA'] = $rows_related["GIA"];
    $related['ĐVT'] = $rows_related["ĐVT"];
    $related['HINHANH'] = base64_encode($rows_related["HINHANH"]);
    array_push($mang_related, $related);
}
$jsondata['mamathangtuongtu'] = $mang_related;

echo json_encode($jsondata);
mysqli_close($conn);
