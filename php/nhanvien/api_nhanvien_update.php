<?php
require_once(__DIR__ . "/../server.php");

$manv = $_POST['MANV'];

$updates = [];
$params = [];

if (!empty($_POST['HOTEN'])) {
    $updates[] = "hoten = ?";
    $params[] = $_POST['HOTEN'];
}

if (!empty($_POST['PHAI'])) {
    $updates[] = "phai = ?";
    $params[] = $_POST['PHAI'];
}

if (!empty($_POST['NTNS'])) {
    $updates[] = "ntns = ?";
    $params[] = $_POST['NTNS'];
}

if (!empty($_POST['CCCD'])) {
    $updates[] = "cccd = ?";
    $params[] = $_POST['CCCD'];
}

if (!empty($_POST['SĐT'])) {
    $updates[] = "SĐT = ?";
    $params[] = $_POST['SĐT'];
}

if (!empty($_POST['GMAIL'])) {
    $updates[] = "gmail = ?";
    $params[] = $_POST['GMAIL'];
}

if (!empty($_POST['ĐC'])) {
    $updates[] = "ĐC = ?";
    $params[] = $_POST['ĐC'];
}

if (!empty($_POST['NGAYVAOLAM'])) {
    $updates[] = "ngayvaolam = ?";
    $params[] = $_POST['NGAYVAOLAM'];
}

if (!empty($_POST['HESOLUONG'])) {
    $updates[] = "hesoluong = ?";
    $params[] = $_POST['HESOLUONG'];
}

if (!empty($_POST['MACV'])) {
    $updates[] = "macv = ?";
    $params[] = $_POST['MACV'];
}

if (count($updates) > 0) {
    $sql = "UPDATE nhanvien SET " . implode(", ", $updates) . " WHERE manv = ?";
    $stmt = $conn->prepare($sql);

    $params[] = $manv;

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
