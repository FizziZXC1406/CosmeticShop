<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With,Authorization,Content-Type');
header('Access-Control-Max-Age: 86400');
if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
    exit();
}

require_once("server.php");
$mamh = $_POST['mamh'];
$rs = mysqli_query($conn, "SELECT COUNT(*) AS 'total' FROM mathang WHERE mamh='" . $mamh . "' ");
$row = mysqli_fetch_array($rs);
if ((int) $row['total'] > 0) {
    $sql = "DELETE FROM mathang WHERE mamh='" . $mamh . "'";
    if (mysqli_query($conn, $sql)) {
        if (mysqli_affected_rows($conn) > 0) {
            $res["success"] = 1;//update thành công
        } else {
            $res["success"] = 0;//update thất bại
        }
    } else {
        $res["success"] = 0;//update thất bại
    }
} else {
    $res["success"] = 2;//mamh không tồn tại trong csdl
}
echo json_encode($res);
mysqli_close($conn);