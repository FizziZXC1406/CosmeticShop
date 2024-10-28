<?php
require_once(__DIR__ . "/../server.php");

// Lấy dữ liệu từ POST
$magiohang = $_POST['MAGIOHANG'];

// Tạo mảng để lưu các cột cần cập nhật
$updates = [];
$params = [];

// Kiểm tra xem có dữ liệu cần cập nhật không
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

// Kiểm tra xem có bất kỳ trường nào cần cập nhật không
if (count($updates) > 0) {
    // Tạo câu lệnh SQL update
    $sql = "UPDATE giohang SET " . implode(", ", $updates) . " WHERE magiohang = ?";
    $stmt = $conn->prepare($sql);

    // Thêm mã giỏ hàng vào cuối mảng params
    $params[] = $magiohang;

    // Xây dựng các kiểu dữ liệu cho bind_param
    $types = str_repeat('s', count($params) - 1) . 's'; // tất cả đều là string
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $res["success"] = 1; // Cập nhật thành công
        } else {
            $res["success"] = 0; // Không có dòng nào bị ảnh hưởng
        }
    } else {
        $res["success"] = 0; // Lỗi khi thực thi câu lệnh SQL
    }
} else {
    $res["success"] = 3; // Không có trường nào cần cập nhật
}

echo json_encode($res);
$stmt->close();
mysqli_close($conn);
