<?php
require_once(__DIR__ . "/../server.php");

// Lấy dữ liệu từ POST
$magiohang = $_POST['MAGIOHANG'];

// Kiểm tra xem mã giỏ hàng đã tồn tại chưa
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM giohang WHERE magiohang = ?");
$stmt->bind_param("s", $magiohang);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();

if ((int) $row['total'] > 0) {
    // Nếu tồn tại, thực hiện câu lệnh DELETE
    $stmt = $conn->prepare("DELETE FROM giohang WHERE magiohang = ?");
    $stmt->bind_param("s", $magiohang);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $res["success"] = 1; // Xóa thành công
        } else {
            $res["success"] = 0; // Không có dòng nào bị ảnh hưởng
        }
    } else {
        $res["success"] = 0; // Lỗi khi thực thi câu lệnh SQL
    }
} else {
    $res["success"] = 2; // Mã giỏ hàng không tồn tại
}

echo json_encode($res);
$stmt->close();
mysqli_close($conn);
