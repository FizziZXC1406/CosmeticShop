<?php
require_once(__DIR__ . "/../server.php");

$maloaikh = $_POST['MALOAIKH'];
$tenloaikh = $_POST['TENLOAIKH'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM loaikh WHERE maloaikh = ?");
$stmt->bind_param("s", $maloaikh);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();

if ((int)$row['total'] > 0) {
    $stmt = $conn->prepare("UPDATE loaikh SET tenloaikh = ? WHERE maloaikh = ?");
    $stmt->bind_param("ss", $tenloaikh, $maloaikh);
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $res["success"] = 1;
        } else {
            $res["success"] = 0;
        }
    } else {
        $res["success"] = 0;
    }
} else {
    $res["success"] = 2;
}

echo json_encode($res);
$stmt->close();
mysqli_close($conn);
