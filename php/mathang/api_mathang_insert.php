<?php
require_once(__DIR__ . "/../server.php");

// Lấy dữ liệu từ POST
$mamh = $_POST['MAMH'];
$manhommh = $_POST['MANHOMMH'];
$tenmh = $_POST['TENMH'];
$tennhanhang = $_POST['TENNHANHANG'];
$mota = $_POST['MOTA'];
$xuatxu = $_POST['XUATXU'];
$gia = $_POST['GIA'];
$dvt = $_POST['ĐVT'];

// Kiểm tra xem mã mặt hàng đã tồn tại chưa
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM mathang WHERE mamh = ?");
$stmt->bind_param("s", $mamh);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();

if ((int) $row['total'] > 0) {
    $res["success"] = 2; // Mã mặt hàng đã tồn tại
} else {
    // Kiểm tra xem có file hình ảnh trong request không
    if (isset($_FILES['HINHANH']) && $_FILES['HINHANH']['error'] === UPLOAD_ERR_OK) {
        // Lấy đường dẫn tạm thời của file
        $tmpFilePath = $_FILES['HINHANH']['tmp_name'];

        // Đọc nội dung file và chuyển đổi sang định dạng BLOB
        $hinhanh = addslashes(file_get_contents($tmpFilePath));

        // Thực hiện câu lệnh INSERT
        $stmt = $conn->prepare("INSERT INTO mathang (mamh, manhommh, tenmh, tennhanhang, mota, xuatxu, gia, ĐVT, hinhanh) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $mamh, $manhommh, $tenmh, $tennhanhang, $mota, $xuatxu, $gia, $dvt, $hinhanh);

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
