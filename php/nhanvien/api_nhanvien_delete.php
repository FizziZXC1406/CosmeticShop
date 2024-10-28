<?php
require_once(__DIR__ . "/../server.php");

// Lấy dữ liệu từ POST
$manv = $_POST['MANV'];

// Kiểm tra xem mã nhân viên có tồn tại trong cơ sở dữ liệu không
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM nhanvien WHERE manv = ?");
$stmt->bind_param("s", $manv);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();

if ((int)$row['total'] > 0) {
    // Mã nhân viên tồn tại, thực hiện lệnh DELETE
    $stmt = $conn->prepare("DELETE FROM nhanvien WHERE manv = ?");
    $stmt->bind_param("s", $manv);

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
    $res["success"] = 2; // Mã nhân viên không tồn tại
}

echo json_encode($res);
$stmt->close();
mysqli_close($conn);
