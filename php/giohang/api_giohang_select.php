<?php
require_once(__DIR__ . "/../server.php");

// Lấy dữ liệu từ GET
$magiohang = $_GET['MAGIOHANG'] ?? null; // Sử dụng null nếu không có key này

if ($magiohang) {
    // Kiểm tra xem giỏ hàng có tồn tại không
    $stmt = $conn->prepare("SELECT * FROM giohang WHERE magiohang = ?");
    $stmt->bind_param("s", $magiohang);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Nếu tìm thấy thông tin giỏ hàng
        $giohang = $result->fetch_all(MYSQLI_ASSOC); // Lấy tất cả dữ liệu
        $res["success"] = 1; // Thành công
        $res["data"] = $giohang; // Gán dữ liệu vào phần trả về
    } else {
        $res["success"] = 0; // Không tìm thấy giỏ hàng
        $res["message"] = "Giỏ hàng không tồn tại";
    }
} else {
    $res["success"] = 0; // Không có mã giỏ hàng được cung cấp
    $res["message"] = "Mã giỏ hàng không được cung cấp";
}

echo json_encode($res); // Trả về kết quả dạng JSON
$stmt->close();
mysqli_close($conn);
