<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With,Authorization,Content-Type');
header('Access-Control-Max-Age: 86400');
if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
    exit();
}

require_once("server.php");

// NHÓM MẶT HÀNG
$sql_nhommathang = "SELECT * FROM `nhommathang`";
$rs_nhommathang = mysqli_query($conn, $sql_nhommathang);
$mang_nhommathang = array();
while ($rows = mysqli_fetch_array($rs_nhommathang)) {
    $usertemp['MANHOMMH'] = $rows["MANHOMMH"];
    $usertemp['TENNHOMMH'] = $rows["TENNHOMMH"];
    array_push($mang_nhommathang, $usertemp);
}
$jsondata['nhommathang'] = $mang_nhommathang;

// NHÃN HÀNG
$sql_nhanhang = "SELECT MANHANHANG, TENNHANHANG, HINHANH FROM `NHANHANG`";
$rs_nhanhang = mysqli_query($conn, $sql_nhanhang); 
$mang_nhanhang = array();
while ($rows = mysqli_fetch_array($rs_nhanhang)) {
    $usertemp['MANHANHANG'] = $rows["MANHANHANG"];
    $usertemp['TENNHANHANG'] = $rows["TENNHANHANG"];
    $usertemp['HINHANH'] = base64_encode($rows["HINHANH"]);
    array_push($mang_nhanhang, $usertemp);
}
$jsondata['nhanhang'] = $mang_nhanhang;

// MẶT HÀNG
$sql_mathang = "SELECT * FROM `mathang` WHERE MAMH = 'MH00001'";
$rs_mathang = mysqli_query($conn, $sql_mathang);
$mang_mathang = array();
while ($rows = mysqli_fetch_array($rs_mathang)) {
    $usertemp['TENMH'] = $rows["TENMH"];
    $usertemp['TENNHANHANG'] = $rows["TENNHANHANG"];
    $usertemp['MOTA'] = $rows["MOTA"];
    $usertemp['GIA'] = $rows["GIA"];
    $usertemp['ĐVT'] = $rows["ĐVT"];
    $usertemp['HINHANH'] = base64_encode($rows["HINHANH"]);
    array_push($mang_mathang, $usertemp);
}
$jsondata['mathang'] = $mang_mathang;

// MÀU MẶT HÀNG
$sql_maumathang = "SELECT MAMAU, TENMAU, HINHANHMAU FROM `maumathang` WHERE MAMH = 'MH00001'";
$rs_maumathang = mysqli_query($conn, $sql_maumathang);
$mang_maumathang = array();
while ($rows = mysqli_fetch_array($rs_maumathang)) {
    $usertemp['MAMAU'] = $rows["MAMAU"];
    $usertemp['TENMAU'] = $rows["TENMAU"];
    $usertemp['HINHANHMAU'] = base64_encode($rows["HINHANHMAU"]);
    array_push($mang_maumathang, $usertemp);
}
$jsondata['maumathang'] = $mang_maumathang;

// SẢN PHẨM TƯƠNG TỰ
$sql_tuongtu = "SELECT TENMH, GIA, ĐVT, HINHANH FROM `mathang` WHERE MANHOMMH = 'ST1'";
$rs_tuongtu = mysqli_query($conn, $sql_tuongtu);
$mang_tuongtu = array();
while ($rows = mysqli_fetch_array($rs_tuongtu)) {
    $usertemp['TENMH'] = $rows["TENMH"];
    $usertemp['GIA'] = $rows["GIA"];
    $usertemp['ĐVT'] = $rows["ĐVT"];
    $usertemp['HINHANH'] = base64_encode($rows["HINHANH"]);
    array_push($mang_tuongtu, $usertemp);
}
$jsondata['tuongtu'] = $mang_tuongtu;


echo json_encode($jsondata);
mysqli_close($conn);
