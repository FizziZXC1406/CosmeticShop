<?php
require_once(__DIR__ . "/../server.php");

$makh = $_POST['MAKH'];

if (!empty($makh)) {
    $sql = "DELETE FROM khachhang WHERE makh = ?";
    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param("s", $makh);

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
