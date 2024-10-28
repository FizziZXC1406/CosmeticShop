<?php
require_once(__DIR__ . "/../server.php");

$mamau = $_POST['MAMAU'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM maumathang WHERE mamau = ?");
$stmt->bind_param("s", $mamau);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();

if ((int) $row['total'] > 0) {
    $stmt = $conn->prepare("DELETE FROM maumathang WHERE mamau = ?");
    $stmt->bind_param("s", $mamau);

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
