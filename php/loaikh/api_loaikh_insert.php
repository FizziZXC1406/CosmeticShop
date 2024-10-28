<?php
require_once(__DIR__ . "/../server.php");

$maloaikh = $_POST['MALOAIKH'];
$tenloaikh = $_POST['TENLOAIKH'];

// Kiểm tra xem mã loại khách hàng đã tồn tại chưa
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM loaikh WHERE maloaikh = ?");
$stmt->bind_param("s", $maloaikh);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();

if ((int) $row['total'] > 0) {
    $res["success"] = 2; // Mã loại khách hàng đã tồn tại
} else {
    // Nếu không tồn tại, thực hiện câu lệnh INSERT
    $stmt = $conn->prepare("INSERT INTO loaikh (maloaikh, tenloaikh) VALUES (?, ?)");
    $stmt->bind_param("ss", $maloaikh, $tenloaikh);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $res["success"] = 1; // Thêm thành công
        } else {
            $res["success"] = 0; // Không có dòng nào bị ảnh hưởng
        }
    } else {
        $res["success"] = 0; // Lỗi khi thực thi câu lệnh SQL
    }
}

echo json_encode($res);
$stmt->close();
mysqli_close($conn);
