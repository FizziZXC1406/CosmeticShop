<?php
require_once(__DIR__ . "/../server.php");

// Lấy manhanhang từ POST
$manhanhang = $_POST['MANHANHANG'];

// Kiểm tra xem manhanhang có tồn tại trong cơ sở dữ liệu không
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM nhanhang WHERE manhanhang = ?");
$stmt->bind_param("s", $manhanhang);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ((int) $row['total'] > 0) {
    // Nếu tồn tại, thực hiện câu lệnh DELETE
    $stmt = $conn->prepare("DELETE FROM nhanhang WHERE manhanhang = ?");
    $stmt->bind_param("s", $manhanhang);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $res["success"] = 1; // Xóa thành công
        } else {
            $res["success"] = 0; // Không có dòng nào bị ảnh hưởng (có thể do không có sự thay đổi)
        }
    } else {
        $res["success"] = 0; // Lỗi khi thực thi câu lệnh SQL
    }
} else {
    $res["success"] = 2; // manhanhang không tồn tại trong cơ sở dữ liệu
}

echo json_encode($res);

// Đóng statement và kết nối
$stmt->close();
mysqli_close($conn);
