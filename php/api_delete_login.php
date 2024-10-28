<?php
require_once("server.php");
$username = $_POST['username'];
$rs = mysqli_query($conn, "select COUNT(*) as 'total' from users where username='" . $username . "' ");
$row = mysqli_fetch_array($rs);
if ((int) $row['total'] > 0) {
    $sql = "delete from users set username='" . $username . "'";
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
    $res["success"] = 2;//Username không tồn tại trong csdl
}
echo json_encode($res);
mysqli_close($conn);