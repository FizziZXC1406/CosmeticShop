<?php
require_once(__DIR__ . "/../server.php");

$mamh = $_POST['MAMH'];
$manhommh = $_POST['MANHOMMH'];
$tenmh = $_POST['TENMH'];
$tennhanhang = $_POST['TENNHANHANG'];
$mota = $_POST['MOTA'];
$xuatxu = $_POST['XUATXU'];
$gia = $_POST['GIA'];
$dvt = $_POST['ĐVT'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM mathang WHERE mamh = ?");
$stmt->bind_param("s", $mamh);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();

if ((int) $row['total'] > 0) {
    $res["success"] = 2;
} else {
    if (isset($_FILES['HINHANH']) && $_FILES['HINHANH']['error'] === UPLOAD_ERR_OK) {
        $tmpFilePath = $_FILES['HINHANH']['tmp_name'];

        $hinhanh = addslashes(file_get_contents($tmpFilePath));

        $stmt = $conn->prepare("INSERT INTO mathang (mamh, manhommh, tenmh, tennhanhang, mota, xuatxu, gia, ĐVT, hinhanh) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $mamh, $manhommh, $tenmh, $tennhanhang, $mota, $xuatxu, $gia, $dvt, $hinhanh);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $res["success"] = 1;
            } else {
                $res["success"] = 0;
            }
        } else {
            $res["success"] = 0;
        }
    } else {
        $res["success"] = 0;
    }
}

echo json_encode($res);
$stmt->close();
mysqli_close($conn);
