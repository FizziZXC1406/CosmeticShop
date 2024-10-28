<?php
require_once(__DIR__ . "/../server.php");

// Lấy dữ liệu từ POST
$manv = $_POST['MANV'];

// Tạo mảng để lưu các cột cần cập nhật
$updates = [];
$params = [];

// Kiểm tra xem có dữ liệu cần cập nhật không
if (!empty($_POST['HOTEN'])) {
    $updates[] = "hoten = ?";
    $params[] = $_POST['HOTEN'];
}

if (!empty($_POST['PHAI'])) {
    $updates[] = "phai = ?";
    $params[] = $_POST['PHAI'];
}

if (!empty($_POST['NTNS'])) {
    $updates[] = "ntns = ?";
    $params[] = $_POST['NTNS'];
}

if (!empty($_POST['CCCD'])) {
    $updates[] = "cccd = ?";
    $params[] = $_POST['CCCD'];
}

if (!empty($_POST['SĐT'])) {
    $updates[] = "SĐT = ?";
    $params[] = $_POST['SĐT'];
}

if (!empty($_POST['GMAIL'])) {
    $updates[] = "gmail = ?";
    $params[] = $_POST['GMAIL'];
}

if (!empty($_POST['ĐC'])) {
    $updates[] = "ĐC = ?";
    $params[] = $_POST['ĐC'];
}

if (!empty($_POST['NGAYVAOLAM'])) {
    $updates[] = "ngayvaolam = ?";
    $params[] = $_POST['NGAYVAOLAM'];
}

if (!empty($_POST['HESOLUONG'])) {
    $updates[] = "hesoluong = ?";
    $params[] = $_POST['HESOLUONG'];
}

if (!empty($_POST['MACV'])) {
    $updates[] = "macv = ?";
    $params[] = $_POST['MACV'];
}

// Kiểm tra xem có bất kỳ trường nào cần cập nhật không
if (count($updates) > 0) {
    // Tạo câu lệnh SQL update
    $sql = "UPDATE nhanvien SET " . implode(", ", $updates) . " WHERE manv = ?";
    $stmt = $conn->prepare($sql);

    // Thêm mã nhân viên vào cuối mảng params
    $params[] = $manv;

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
