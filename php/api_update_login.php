<?php
require_once("server.php");
$username = $_POST['username'];
$gmail = $_POST['gmail'];
$p = $_POST['password'];
$password = sha1($p);
$rs = mysqli_query($conn, "select COUNT(*) as 'total' from users where username='" . $username . "' ");
$row = mysqli_fetch_array($rs);
if ((int) $row['total'] > 0) {
    $sql = "update users set username='" . $username . "', gmail='" . $gmail . "', password='" . $password . "' where username='" . $username . "'";
    if (mysqli_query($conn, $sql)) {
        if (mysqli_affected_rows($conn) > 0) {
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
mysqli_close($conn);