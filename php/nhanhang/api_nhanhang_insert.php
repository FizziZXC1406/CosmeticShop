<?php
require_once(__DIR__ . "/../server.php");

$manhanhang = $_POST['MANHANHANG'];
$tennhanhang = $_POST['TENNHANHANG'];

// Kiểm tra xem có file hình ảnh trong request không
if (isset($_FILES['HINHANH']) && $_FILES['HINHANH']['error'] === UPLOAD_ERR_OK) {
    // Lấy đường dẫn tạm thời của file
    $tmpFilePath = $_FILES['HINHANH']['tmp_name'];
    
    // Đọc nội dung file
    $hinhanh = file_get_contents($tmpFilePath); // Đọc nội dung file
    
    // Kiểm tra xem mã nhãn hàng đã tồn tại chưa
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM nhanhang WHERE manhanhang = ?");
    $stmt->bind_param("s", $manhanhang);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ((int)$row['total'] > 0) {
        $res["success"] = 2; // Mã nhãn hàng đã tồn tại
    } else {
        // Thực hiện câu lệnh INSERT
        $stmt = $conn->prepare("INSERT INTO nhanhang (manhanhang, tennhanhang, hinhanh) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $manhanhang, $tennhanhang, $hinhanh);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $res["success"] = 1; // Thành công
            } else {
                $res["success"] = 2; // Không có dòng nào bị ảnh hưởng
            }
        } else {
            $res["success"] = 3; // Lỗi khi thực thi câu lệnh SQL
        }
    }

    $stmt->close(); // Đóng statement
} else {
    $res["success"] = 0; // Không có file hình ảnh được gửi hoặc lỗi khi upload file
}

echo json_encode($res);
mysqli_close($conn);
