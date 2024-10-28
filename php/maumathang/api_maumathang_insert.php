<?php
require_once(__DIR__ . "/../server.php");

// Lấy dữ liệu từ POST
$mamh = $_POST['MAMH'];
$mamau = $_POST['MAMAU'];
$tenmau = $_POST['TENMAU'];
$soluong = $_POST['SOLUONG'];

// Kiểm tra xem mã màu đã tồn tại chưa
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM maumathang WHERE mamau = ?");
$stmt->bind_param("s", $mamau);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();

if ((int) $row['total'] > 0) {
    $res["success"] = 2; // Mã màu đã tồn tại
} else {
    // Kiểm tra xem có file hình ảnh trong request không
    if (isset($_FILES['HINHANHMAU']) && $_FILES['HINHANHMAU']['error'] === UPLOAD_ERR_OK) {
        // Lấy đường dẫn tạm thời của file
        $tmpFilePath = $_FILES['HINHANHMAU']['tmp_name'];

        // Đọc nội dung file và chuyển đổi sang định dạng BLOB
        $hinhanhmau = addslashes(file_get_contents($tmpFilePath));

        // Thực hiện câu lệnh INSERT
        $stmt = $conn->prepare("INSERT INTO maumathang (mamh, mamau, tenmau, soluong, hinhanhmau) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $mamh, $mamau, $tenmau, $soluong, $hinhanhmau);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $res["success"] = 1; // Thành công
            } else {
                $res["success"] = 0; // Không có dòng nào bị ảnh hưởng
            }
        } else {
            $res["success"] = 0; // Lỗi khi thực thi câu lệnh SQL
        }
    } else {
        $res["success"] = 0; // Không có file hình ảnh được gửi
    }
}

echo json_encode($res);
$stmt->close();
mysqli_close($conn);
