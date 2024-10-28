<?php
require_once(__DIR__ . "/../server.php");

// Lấy dữ liệu từ POST
$mamau = $_POST['MAMAU'];

// Tạo mảng để lưu các cột cần cập nhật
$updates = [];
$params = [];

// Kiểm tra xem có dữ liệu cần cập nhật không
if (!empty($_POST['TENMAU'])) {
    $updates[] = "tenmau = ?";
    $params[] = $_POST['TENMAU'];
}

if (!empty($_POST['SOLUONG'])) {
    $updates[] = "soluong = ?";
    $params[] = $_POST['SOLUONG'];
}

// Kiểm tra xem có file hình ảnh trong request không
if (isset($_FILES['HINHANHMAU']) && $_FILES['HINHANHMAU']['error'] === UPLOAD_ERR_OK) {
    // Lấy đường dẫn tạm thời của file
    $tmpFilePath = $_FILES['HINHANHMAU']['tmp_name'];

    // Đọc nội dung file và chuyển đổi sang định dạng BLOB
    $hinhanhmau = addslashes(file_get_contents($tmpFilePath));
    $updates[] = "hinhanhmau = ?";
    $params[] = $hinhanhmau;
}

// Kiểm tra xem mã màu đã tồn tại không
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM maumathang WHERE mamau = ?");
$stmt->bind_param("s", $mamau);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();

if ((int) $row['total'] > 0) {
    // Kiểm tra xem có bất kỳ trường nào cần cập nhật không
    if (count($updates) > 0) {
        // Tạo câu lệnh SQL update
        $sql = "UPDATE maumathang SET " . implode(", ", $updates) . " WHERE mamau = ?";
        $stmt = $conn->prepare($sql);

        // Thêm mã màu vào cuối mảng params
        $params[] = $mamau;

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
} else {
    $res["success"] = 2; // Mã màu không tồn tại
}

echo json_encode($res);
$stmt->close();
mysqli_close($conn);
