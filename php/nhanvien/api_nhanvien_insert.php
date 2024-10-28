<?php
require_once(__DIR__ . "/../server.php");

$manv = $_POST['MANV'];
$hoten = $_POST['HOTEN'];
$phai = $_POST['PHAI'];
$ntns = $_POST['NTNS'];
$cccd = $_POST['CCCD'];
$sdt = $_POST['SĐT'];
$email = $_POST['GMAIL'];
$dc = $_POST['ĐC'];
$ngayvaolam = $_POST['NGAYVAOLAM'];
$hesoluong = $_POST['HESOLUONG'];
$macv = $_POST['MACV'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM nhanvien WHERE manv = ?");
$stmt->bind_param("s", $manv);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();

if ((int) $row['total'] > 0) {
    $res["success"] = 2;
} else {
    $stmt = $conn->prepare("INSERT INTO nhanvien (manv, hoten, phai, ntns, cccd, SĐT, gmail, ĐC, ngayvaolam, hesoluong, macv) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $manv, $hoten, $phai, $ntns, $cccd, $sdt, $email, $dc, $ngayvaolam, $hesoluong, $macv);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $res["success"] = 1;
        } else {
            $res["success"] = 0;
        }
    } else {
        $res["success"] = 0;
    }
}

echo json_encode($res);
$stmt->close();
mysqli_close($conn);
