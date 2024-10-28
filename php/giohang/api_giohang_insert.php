<?php
require_once(__DIR__ . "/../server.php");

$magiohang = $_POST['MAGIOHANG'];
$makh = $_POST['MAKH'];
$mamh = $_POST['MAMH'];
$soluong = $_POST['SOLUONG'];
$ngaythem = $_POST['NGAYTHEM'];

$stmt = $conn->prepare("SELECT soluong FROM giohang WHERE magiohang = ? AND makh = ? AND mamh = ?");
$stmt->bind_param("sss", $magiohang, $makh, $mamh);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $existing_soluong = $row['soluong'];

    $new_soluong = $existing_soluong + $soluong;

    $updateStmt = $conn->prepare("UPDATE giohang SET soluong = ?, ngaythem = ? WHERE magiohang = ? AND makh = ? AND mamh = ?");
    $updateStmt->bind_param("issss", $new_soluong, $ngaythem, $magiohang, $makh, $mamh);

    if ($updateStmt->execute()) {
        $res["success"] = 1;
    } else {
        $res["success"] = 0;
    }

    $updateStmt->close();
} else {
    $stmt = $conn->prepare("INSERT INTO giohang (magiohang, makh, mamh, soluong, ngaythem) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $magiohang, $makh, $mamh, $soluong, $ngaythem);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $res["success"] = 1;
        } else {
            $res["success"] = 0;
        }
    } else {
        $res["success"] = 0;
    }

    $stmt->close();
}

echo json_encode($res);
mysqli_close($conn);
