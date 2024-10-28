<?php
require_once(__DIR__ . "/../server.php");

// Lấy dữ liệu từ POST
$magiohang = $_POST['MAGIOHANG'];
$makh = $_POST['MAKH'];
$mamh = $_POST['MAMH'];
$soluong = $_POST['SOLUONG'];
$ngaythem = $_POST['NGAYTHEM'];

// Kiểm tra xem giỏ hàng đã tồn tại chưa
$stmt = $conn->prepare("SELECT soluong FROM giohang WHERE magiohang = ? AND makh = ? AND mamh = ?");
$stmt->bind_param("sss", $magiohang, $makh, $mamh);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Nếu giỏ hàng đã tồn tại, lấy số lượng hiện tại
    $row = $result->fetch_assoc();
    $existing_soluong = $row['soluong'];

    // Cộng số lượng mới vào số lượng hiện có
    $new_soluong = $existing_soluong + $soluong;

    // Cập nhật lại số lượng trong giỏ hàng
    $updateStmt = $conn->prepare("UPDATE giohang SET soluong = ?, ngaythem = ? WHERE magiohang = ? AND makh = ? AND mamh = ?");
    $updateStmt->bind_param("issss", $new_soluong, $ngaythem, $magiohang, $makh, $mamh);

    if ($updateStmt->execute()) {
        $res["success"] = 1; // Cập nhật thành công
    } else {
        $res["success"] = 0; // Lỗi khi thực thi câu lệnh SQL
    }

    $updateStmt->close();
} else {
    // Nếu giỏ hàng chưa tồn tại, thực hiện câu lệnh INSERT
    $stmt = $conn->prepare("INSERT INTO giohang (magiohang, makh, mamh, soluong, ngaythem) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $magiohang, $makh, $mamh, $soluong, $ngaythem);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $res["success"] = 1; // Thành công
        } else {
            $res["success"] = 0; // Không có dòng nào bị ảnh hưởng
        }
    } else {
        $res["success"] = 0; // Lỗi khi thực thi câu lệnh SQL
    }

    $stmt->close();
}

echo json_encode($res);
mysqli_close($conn);
