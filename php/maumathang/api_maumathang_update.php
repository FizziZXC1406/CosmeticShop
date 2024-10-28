<?php
require_once(__DIR__ . "/../server.php");

$mamau = $_POST['MAMAU'];

$updates = [];
$params = [];

if (!empty($_POST['TENMAU'])) {
    $updates[] = "tenmau = ?";
    $params[] = $_POST['TENMAU'];
}

if (!empty($_POST['SOLUONG'])) {
    $updates[] = "soluong = ?";
    $params[] = $_POST['SOLUONG'];
}

if (isset($_FILES['HINHANHMAU']) && $_FILES['HINHANHMAU']['error'] === UPLOAD_ERR_OK) {
    $tmpFilePath = $_FILES['HINHANHMAU']['tmp_name'];
    $hinhanhmau = addslashes(file_get_contents($tmpFilePath));
    $updates[] = "hinhanhmau = ?";
    $params[] = $hinhanhmau;
}

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM maumathang WHERE mamau = ?");
$stmt->bind_param("s", $mamau);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();

if ((int) $row['total'] > 0) {
    if (count($updates) > 0) {
        $sql = "UPDATE maumathang SET " . implode(", ", $updates) . " WHERE mamau = ?";
        $stmt = $conn->prepare($sql);
        $params[] = $mamau;

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
} else {
    $res["success"] = 2;
}

echo json_encode($res);
$stmt->close();
mysqli_close($conn);
