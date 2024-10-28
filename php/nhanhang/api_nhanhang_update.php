<?php
require_once(__DIR__ . "/../server.php");

$manhanhang = $_POST['MANHANHANG'];
$tennhanhang = $_POST['TENNHANHANG'];

// Kiểm tra xem có file hình ảnh trong request không
if (isset($_FILES['HINHANH']) && $_FILES['HINHANH']['error'] === UPLOAD_ERR_OK) {
    // Lấy đường dẫn tạm thời của file
    $tmpFilePath = $_FILES['HINHANH']['tmp_name'];

    // Đọc nội dung file
    $hinhanh = file_get_contents($tmpFilePath); // Không cần addslashes vì dùng bind_param

    // Kiểm tra xem mã nhãn hàng có tồn tại không
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM nhanhang WHERE manhanhang = ?");
    $stmt->bind_param("s", $manhanhang);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ((int) $row['total'] > 0) {
        // Nếu mã nhãn hàng tồn tại, thực hiện câu lệnh UPDATE
        $stmt = $conn->prepare("UPDATE nhanhang SET tennhanhang = ?, hinhanh = ? WHERE manhanhang = ?");
        $stmt->bind_param("sss", $tennhanhang, $hinhanh, $manhanhang);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $res["success"] = 1; // Cập nhật thành công
            } else {
                $res["success"] = 0; // Không có dòng nào bị ảnh hưởng (có thể dữ liệu không thay đổi)
            }
        } else {
            $res["success"] = 0; // Lỗi khi thực thi câu lệnh SQL
        }
    } else {
        $res["success"] = 2; // Mã nhãn hàng không tồn tại
    }

    $stmt->close(); // Đóng statement
} else {
    $res["success"] = 0; // Không có file hình ảnh được gửi hoặc lỗi khi upload file
}

echo json_encode($res);
mysqli_close($conn);
