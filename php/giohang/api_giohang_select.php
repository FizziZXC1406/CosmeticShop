<?php
require_once(__DIR__ . "/../server.php");

$magiohang = $_GET['MAGIOHANG'] ?? null;

if ($magiohang) {
    $stmt = $conn->prepare("SELECT * FROM giohang WHERE magiohang = ?");
    $stmt->bind_param("s", $magiohang);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $giohang = $result->fetch_all(MYSQLI_ASSOC);
        $res["success"] = 1;
        $res["data"] = $giohang;
    } else {
        $res["success"] = 0;
        $res["message"] = "Giỏ hàng không tồn tại";
    }
} else {
    $res["success"] = 0;
    $res["message"] = "Mã giỏ hàng không được cung cấp";
}

echo json_encode($res);
$stmt->close();
mysqli_close($conn);
