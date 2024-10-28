<?php
require_once(__DIR__ . "/../server.php");

$mamh = $_POST['MAMH'];

$updates = [];
$params = [];

if (!empty($_POST['MANHOMMH'])) {
    $updates[] = "manhommh = ?";
    $params[] = $_POST['MANHOMMH'];
}

if (!empty($_POST['TENMH'])) {
    $updates[] = "tenmh = ?";
    $params[] = $_POST['TENMH'];
}

if (!empty($_POST['TENNHANHANG'])) {
    $updates[] = "tennhanhang = ?";
    $params[] = $_POST['TENNHANHANG'];
}

if (!empty($_POST['MOTA'])) {
    $updates[] = "mota = ?";
    $params[] = $_POST['MOTA'];
}

if (!empty($_POST['XUATXU'])) {
    $updates[] = "xuatxu = ?";
    $params[] = $_POST['XUATXU'];
}

if (!empty($_POST['GIA'])) {
    $updates[] = "gia = ?";
    $params[] = $_POST['GIA'];
}

if (!empty($_POST['ĐVT'])) {
    $updates[] = "ĐVT = ?";
    $params[] = $_POST['ĐVT'];
}

if (isset($_FILES['HINHANH']) && $_FILES['HINHANH']['error'] === UPLOAD_ERR_OK) {
    $tmpFilePath = $_FILES['HINHANH']['tmp_name'];
    $hinhanh = addslashes(file_get_contents($tmpFilePath));
    $updates[] = "hinhanh = ?";
    $params[] = $hinhanh;
}

if (count($updates) > 0) {
    $sql = "UPDATE mathang SET " . implode(", ", $updates) . " WHERE mamh = ?";
    $stmt = $conn->prepare($sql);

    $params[] = $mamh;

    $types = str_repeat('s', count($params) - 1) . 's';
    $stmt->bind_param($types, ...$params);

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
    $res["success"] = 3;
}

echo json_encode($res);
$stmt->close();
mysqli_close($conn);
