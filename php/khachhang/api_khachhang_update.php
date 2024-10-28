<?php
require_once(__DIR__ . "/../server.php");

// Lấy dữ liệu từ POST
$makh = $_POST['MAKH'];

// Tạo mảng để lưu các cột cần cập nhật
$updates = [];
$params = [];

// Kiểm tra xem có dữ liệu cần cập nhật không
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

// Kiểm tra xem có bất kỳ trường nào cần cập nhật không
if (count($updates) > 0) {
    // Tạo câu lệnh SQL update
    $sql = "UPDATE khachhang SET " . implode(", ", $updates) . " WHERE makh = ?";
    $stmt = $conn->prepare($sql);

    // Thêm mã khách hàng vào cuối mảng params
    $params[] = $makh;

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
