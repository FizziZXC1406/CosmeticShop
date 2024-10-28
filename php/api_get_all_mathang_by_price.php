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

$s = $_GET['search'];
$dau = $_GET['dau'];
$sql = '';
if ($dau == 'greaterThan') {
    $sql = "SELECT mathang.MAMH, mathang.MANHOMMH, nhommathang.TENNHOMMH, mathang.TENMH, 
        mathang.TENNHANHANG, mathang.MOTA, mathang.XUATXU, mathang.GIA, mathang.ĐVT, 
        mathang.HINHANH, SUM(maumathang.soluong) AS TONGSOLUONG
        FROM mathang
        JOIN nhommathang ON mathang.manhommh = nhommathang.manhommh
        LEFT JOIN maumathang ON mathang.MAMH = maumathang.MAMH
        WHERE mathang.Gia > '$s'
        GROUP BY mathang.MAMH";
} else if ($dau == 'smallerThan') {
    $sql = "SELECT mathang.MAMH, mathang.MANHOMMH, nhommathang.TENNHOMMH, mathang.TENMH, 
        mathang.TENNHANHANG, mathang.MOTA, mathang.XUATXU, mathang.GIA, mathang.ĐVT, 
        mathang.HINHANH, SUM(maumathang.soluong) AS TONGSOLUONG
        FROM mathang
        JOIN nhommathang ON mathang.manhommh = nhommathang.manhommh
        LEFT JOIN maumathang ON mathang.MAMH = maumathang.MAMH
        WHERE mathang.Gia < '$s'
        GROUP BY mathang.MAMH";
} else if ($dau == 'greaterThanEqual') {
    $sql = "SELECT mathang.MAMH, mathang.MANHOMMH, nhommathang.TENNHOMMH, mathang.TENMH, 
        mathang.TENNHANHANG, mathang.MOTA, mathang.XUATXU, mathang.GIA, mathang.ĐVT, 
        mathang.HINHANH, SUM(maumathang.soluong) AS TONGSOLUONG
        FROM mathang
        JOIN nhommathang ON mathang.manhommh = nhommathang.manhommh
        LEFT JOIN maumathang ON mathang.MAMH = maumathang.MAMH
        WHERE mathang.Gia >= '$s'
        GROUP BY mathang.MAMH";
} else if ($dau == "smallerThanEqual") {
    $sql = "SELECT mathang.MAMH, mathang.MANHOMMH, nhommathang.TENNHOMMH, mathang.TENMH, 
        mathang.TENNHANHANG, mathang.MOTA, mathang.XUATXU, mathang.GIA, mathang.ĐVT, 
        mathang.HINHANH, SUM(maumathang.soluong) AS TONGSOLUONG
        FROM mathang
        JOIN nhommathang ON mathang.manhommh = nhommathang.manhommh
        LEFT JOIN maumathang ON mathang.MAMH = maumathang.MAMH
        WHERE mathang.Gia <= '$s'
        GROUP BY mathang.MAMH";
} else if ($dau == "equal") {
    $sql = "SELECT mathang.MAMH, mathang.MANHOMMH, nhommathang.TENNHOMMH, mathang.TENMH, 
        mathang.TENNHANHANG, mathang.MOTA, mathang.XUATXU, mathang.GIA, mathang.ĐVT, 
        mathang.HINHANH, SUM(maumathang.soluong) AS TONGSOLUONG
        FROM mathang
        JOIN nhommathang ON mathang.manhommh = nhommathang.manhommh
        LEFT JOIN maumathang ON mathang.MAMH = maumathang.MAMH
        WHERE mathang.Gia = '$s'
        GROUP BY mathang.MAMH";
}

$rs = mysqli_query($conn, $sql);

if (!$rs) {
    echo json_encode(array('error' => mysqli_error($conn))); // Gửi thông báo lỗi nếu truy vấn không thành công
    exit();
}

$mang = array();
while ($rows = mysqli_fetch_array($rs)) {
    $usertemp = array();
    $usertemp['MAMH'] = $rows["MAMH"];
    $usertemp['MANHOMMH'] = $rows["MANHOMMH"];
    $usertemp['TENNHOMMH'] = $rows["TENNHOMMH"];
    $usertemp['TENMH'] = $rows["TENMH"];
    $usertemp['TENNHANHANG'] = $rows["TENNHANHANG"];
    $usertemp['MOTA'] = $rows["MOTA"];
    $usertemp['XUATXU'] = $rows["XUATXU"];
    $usertemp['GIA'] = $rows["GIA"];
    $usertemp['ĐVT'] = $rows["ĐVT"];
    $usertemp['HINHANH'] = base64_encode($rows["HINHANH"]);
    $usertemp['TONGSOLUONG'] = $rows["TONGSOLUONG"]; // Lấy tổng số lượng
    array_push($mang, $usertemp);
}

$jsondata['tatcamathang'] = $mang;
echo json_encode($jsondata);
mysqli_close($conn);
