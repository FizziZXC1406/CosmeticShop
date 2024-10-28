<?php
require_once(__DIR__ . "/../server.php");

// Lấy dữ liệu từ POST
$mamau = $_POST['MAMAU'];

// Kiểm tra xem mã màu đã tồn tại không
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM maumathang WHERE mamau = ?");
$stmt->bind_param("s", $mamau);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();

if ((int) $row['total'] > 0) {
    // Thực hiện câu lệnh DELETE
    $stmt = $conn->prepare("DELETE FROM maumathang WHERE mamau = ?");
    $stmt->bind_param("s", $mamau);

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
    $res["success"] = 2; // Mã màu không tồn tại
}

echo json_encode($res);
$stmt->close();
mysqli_close($conn);
