<?php
require_once(__DIR__ . "/../server.php");

// Lấy dữ liệu từ POST
$makh = $_POST['MAKH'];
$hotenkh = $_POST['HOTENKH'];
$phaikh = $_POST['PHAIKH'];
$ntnskh = $_POST['NTNSKH']; // Ngày tháng năm sinh
$cccdkh = $_POST['CCCDKH']; // Số chứng minh thư
$sdtkh = $_POST['SĐTKH']; // Số điện thoại
$emailkh = $_POST['GMAILKH'];
$dckh = $_POST['ĐCKH']; // Địa chỉ
$maloaikh = $_POST['MALOAIKH'];

// Kiểm tra xem mã khách hàng đã tồn tại chưa
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM khachhang WHERE makh = ?");
$stmt->bind_param("s", $makh);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();

if ((int) $row['total'] > 0) {
    $res["success"] = 2; // Mã khách hàng đã tồn tại
} else {
    // Thực hiện câu lệnh INSERT
    $stmt = $conn->prepare("INSERT INTO khachhang (makh, hotenkh, phaikh, ntnskh, cccdkh, sđtkh, gmailkh, đckh, maloaikh) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $makh, $hotenkh, $phaikh, $ntnskh, $cccdkh, $sdtkh, $emailkh, $dckh, $maloaikh);

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
