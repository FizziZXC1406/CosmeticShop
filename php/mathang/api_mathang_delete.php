<?php
require_once(__DIR__ . "/../server.php");

// Lấy dữ liệu từ POST
$mamh = $_POST['MAMH'];

// Kiểm tra xem mã mặt hàng có tồn tại không
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM mathang WHERE mamh = ?");
$stmt->bind_param("s", $mamh);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();

if ((int)$row['total'] > 0) {
    // Nếu tồn tại, thực hiện câu lệnh DELETE
    $stmt = $conn->prepare("DELETE FROM mathang WHERE mamh = ?");
    $stmt->bind_param("s", $mamh);
    
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
    $res["success"] = 2; // Mã mặt hàng không tồn tại
}

echo json_encode($res);
$stmt->close();
mysqli_close($conn);
