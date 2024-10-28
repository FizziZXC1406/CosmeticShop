<?php
require_once(__DIR__ . "/../server.php");

$maloaikh = $_POST['MALOAIKH'];

// Kiểm tra xem mã loại khách hàng đã tồn tại chưa
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM loaikh WHERE maloaikh = ?");
$stmt->bind_param("s", $maloaikh);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();

if ((int)$row['total'] > 0) {
    // Nếu tồn tại, thực hiện câu lệnh DELETE
    $stmt = $conn->prepare("DELETE FROM loaikh WHERE maloaikh = ?");
    $stmt->bind_param("s", $maloaikh);
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
    $res["success"] = 2; // Mã loại khách hàng không tồn tại
}

echo json_encode($res);
$stmt->close();
mysqli_close($conn);
