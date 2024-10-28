<?php
require_once(__DIR__ . "/../server.php");

// Lấy dữ liệu từ POST
$mamh = $_POST['MAMH'];

// Tạo mảng để lưu các cột cần cập nhật
$updates = [];
$params = [];

// Kiểm tra xem có dữ liệu cần cập nhật không
if (!empty($_POST['MANHOMMH'])) {
    $updates[] = "manhommh = ?";
    $params[] = $_POST['MANHOMMH'];
}

if (!empty($_POST['TENMH'])) {
    $updates[] = "tenmh = ?";
    $params[] = $_POST['TENMH'];
}

if (!empty($_POST['TENNHANHANG'])) {
    $updates[] = "tennhanhang = ?";
    $params[] = $_POST['TENNHANHANG'];
}

if (!empty($_POST['MOTA'])) {
    $updates[] = "mota = ?";
    $params[] = $_POST['MOTA'];
}

if (!empty($_POST['XUATXU'])) {
    $updates[] = "xuatxu = ?";
    $params[] = $_POST['XUATXU'];
}

if (!empty($_POST['GIA'])) {
    $updates[] = "gia = ?";
    $params[] = $_POST['GIA'];
}

if (!empty($_POST['ĐVT'])) {
    $updates[] = "ĐVT = ?";
    $params[] = $_POST['ĐVT'];
}

// Kiểm tra xem có file hình ảnh trong request không
if (isset($_FILES['HINHANH']) && $_FILES['HINHANH']['error'] === UPLOAD_ERR_OK) {
    $tmpFilePath = $_FILES['HINHANH']['tmp_name'];
    $hinhanh = addslashes(file_get_contents($tmpFilePath));
    $updates[] = "hinhanh = ?";
    $params[] = $hinhanh;
}

// Kiểm tra xem có bất kỳ trường nào cần cập nhật không
if (count($updates) > 0) {
    // Tạo câu lệnh SQL update
    $sql = "UPDATE mathang SET " . implode(", ", $updates) . " WHERE mamh = ?";
    $stmt = $conn->prepare($sql);

    // Thêm mã mặt hàng vào cuối mảng params
    $params[] = $mamh;

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
