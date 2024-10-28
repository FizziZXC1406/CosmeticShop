<?php
require_once(__DIR__ . "/../server.php");

// Lấy dữ liệu từ POST
$macv = $_POST['MACV'];
$tencv = $_POST['TENCV'];

// Kiểm tra xem mã chức vụ đã tồn tại chưa
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM chucvu WHERE macv = ?");
$stmt->bind_param("s", $macv);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();

if ((int) $row['total'] > 0) {
    $res["success"] = 2; // Mã chức vụ đã tồn tại
} else {
    // Thực hiện câu lệnh INSERT
    $stmt = $conn->prepare("INSERT INTO chucvu (macv, tencv) VALUES (?, ?)");
    $stmt->bind_param("ss", $macv, $tencv);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $res["success"] = 1; // Thành công
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
