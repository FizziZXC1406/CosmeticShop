<?php
require_once(__DIR__ . "/../server.php");

$makh = $_POST['MAKH'];

$updates = [];
$params = [];

if (!empty($_POST['HOTENKH'])) {
    $updates[] = "hotenkh = ?";
    $params[] = $_POST['HOTENKH'];
}

if (!empty($_POST['PHAIKH'])) {
    $updates[] = "phaikh = ?";
    $params[] = $_POST['PHAIKH'];
}

if (!empty($_POST['NTNSKH'])) {
    $updates[] = "ntnskh = ?";
    $params[] = $_POST['NTNSKH'];
}

if (!empty($_POST['CCCDKH'])) {
    $updates[] = "cccdkh = ?";
    $params[] = $_POST['CCCDKH'];
}

if (!empty($_POST['SĐTKH'])) {
    $updates[] = "sđtkh = ?";
    $params[] = $_POST['SĐTKH'];
}

if (!empty($_POST['GMAILKH'])) {
    $updates[] = "gmailkh = ?";
    $params[] = $_POST['GMAILKH'];
}

if (!empty($_POST['ĐCKH'])) {
    $updates[] = "đckh = ?";
    $params[] = $_POST['ĐCKH'];
}

if (!empty($_POST['MALOAIKH'])) {
    $updates[] = "maloaikh = ?";
    $params[] = $_POST['MALOAIKH'];
}

if (count($updates) > 0) {
    $sql = "UPDATE khachhang SET " . implode(", ", $updates) . " WHERE makh = ?";
    $stmt = $conn->prepare($sql);

    $params[] = $makh;

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
