<?php
require_once(__DIR__ . "/../server.php");

$manhanhang = $_POST['MANHANHANG'];
$tennhanhang = $_POST['TENNHANHANG'];

if (isset($_FILES['HINHANH']) && $_FILES['HINHANH']['error'] === UPLOAD_ERR_OK) {
    $tmpFilePath = $_FILES['HINHANH']['tmp_name'];
    
    $hinhanh = file_get_contents($tmpFilePath);

    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM nhanhang WHERE manhanhang = ?");
    $stmt->bind_param("s", $manhanhang);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ((int)$row['total'] > 0) {
        $res["success"] = 2;
    } else {
        $stmt = $conn->prepare("INSERT INTO nhanhang (manhanhang, tennhanhang, hinhanh) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $manhanhang, $tennhanhang, $hinhanh);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $res["success"] = 1;
            } else {
                $res["success"] = 2;
            }
        } else {
            $res["success"] = 3;
        }
    }
    $stmt->close();
} else {
    $res["success"] = 0;
}

echo json_encode($res);
mysqli_close($conn);
