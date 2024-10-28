<?php
require_once(__DIR__ . "/../server.php");

$manhanhang = $_POST['MANHANHANG'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM nhanhang WHERE manhanhang = ?");
$stmt->bind_param("s", $manhanhang);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ((int) $row['total'] > 0) {
    $stmt = $conn->prepare("DELETE FROM nhanhang WHERE manhanhang = ?");
    $stmt->bind_param("s", $manhanhang);

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
