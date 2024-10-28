<?php
require_once(__DIR__ . "/../server.php");

$mamh = $_POST['MAMH'];
$mamau = $_POST['MAMAU'];
$tenmau = $_POST['TENMAU'];
$soluong = $_POST['SOLUONG'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM maumathang WHERE mamau = ?");
$stmt->bind_param("s", $mamau);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();

if ((int) $row['total'] > 0) {
    $res["success"] = 2;
} else {
    if (isset($_FILES['HINHANHMAU']) && $_FILES['HINHANHMAU']['error'] === UPLOAD_ERR_OK) {
        $tmpFilePath = $_FILES['HINHANHMAU']['tmp_name'];

        $hinhanhmau = addslashes(file_get_contents($tmpFilePath));

        $stmt = $conn->prepare("INSERT INTO maumathang (mamh, mamau, tenmau, soluong, hinhanhmau) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $mamh, $mamau, $tenmau, $soluong, $hinhanhmau);

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
