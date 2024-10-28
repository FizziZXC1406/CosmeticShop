<?php
require_once(__DIR__ . "/../server.php");

$makh = $_POST['MAKH'];
$hotenkh = $_POST['HOTENKH'];
$phaikh = $_POST['PHAIKH'];
$ntnskh = $_POST['NTNSKH'];
$cccdkh = $_POST['CCCDKH'];
$sdtkh = $_POST['SĐTKH'];
$emailkh = $_POST['GMAILKH'];
$dckh = $_POST['ĐCKH'];
$maloaikh = $_POST['MALOAIKH'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM khachhang WHERE makh = ?");
$stmt->bind_param("s", $makh);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();

if ((int) $row['total'] > 0) {
    $res["success"] = 2;
} else {

    $stmt = $conn->prepare("INSERT INTO khachhang (makh, hotenkh, phaikh, ntnskh, cccdkh, sđtkh, gmailkh, đckh, maloaikh) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $makh, $hotenkh, $phaikh, $ntnskh, $cccdkh, $sdtkh, $emailkh, $dckh, $maloaikh);

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
