<?php
require_once(__DIR__ . "/../server.php");

// Lấy dữ liệu từ POST
$makh = $_POST['MAKH'];

// Kiểm tra xem mã khách hàng có được cung cấp không
if (!empty($makh)) {
    // Tạo câu lệnh SQL DELETE
    $sql = "DELETE FROM khachhang WHERE makh = ?";
    $stmt = $conn->prepare($sql);
    
    // Liên kết tham số
    $stmt->bind_param("s", $makh);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $res["success"] = 1; // Xóa thành công
        } else {
            $res["success"] = 0; // Không có dòng nào bị ảnh hưởng (mã khách hàng không tồn tại)
        }
    } else {
        $res["success"] = 0; // Lỗi khi thực thi câu lệnh SQL
    }
} else {
    $res["success"] = 2; // Mã khách hàng không được cung cấp
}

echo json_encode($res);
$stmt->close();
mysqli_close($conn);
