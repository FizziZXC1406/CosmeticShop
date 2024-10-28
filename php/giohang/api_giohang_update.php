<?php
require_once(__DIR__ . "/../server.php");

$magiohang = $_POST['MAGIOHANG'];

$updates = [];
$params = [];

if (!empty($_POST['MAKH'])) {
    $updates[] = "makh = ?";
    $params[] = $_POST['MAKH'];
}

if (!empty($_POST['MAMH'])) {
    $updates[] = "mamh = ?";
    $params[] = $_POST['MAMH'];
}

if (!empty($_POST['SOLUONG'])) {
    $updates[] = "soluong = ?";
    $params[] = $_POST['SOLUONG'];
}

if (!empty($_POST['NGAYTHEM'])) {
    $updates[] = "ngaythem = ?";
    $params[] = $_POST['NGAYTHEM'];
}

if (count($updates) > 0) {
    $sql = "UPDATE giohang SET " . implode(", ", $updates) . " WHERE magiohang = ?";
    $stmt = $conn->prepare($sql);

    $params[] = $magiohang;

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
