<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With,Authorization,Content-Type');
header('Access-Control-Max-Age: 86400');
if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
    exit();
}

require_once("server.php");

$username = $_POST['username'];
$gmail = $_POST['gmail'];
$p = $_POST["password"];
$password = sha1($p);
$role = $_POST['role'];

if (empty($role)) {
    $role = 1;
}

$rs = mysqli_query($conn, "select COUNT(*) as 'total' from users where username='" . $username . "' ");
$row = mysqli_fetch_array($rs);

if ((int) $row['total'] > 0) {
    $res["success"] = 2;
} else {
    $sql = "INSERT INTO `users`(username, gmail, password, role) 
            VALUES ('" . $username . "','" . $gmail . "','" . $password . "', '" . $role . "')";
    if (mysqli_query($conn, $sql)) {
        if (mysqli_affected_rows($conn) > 0) {
            $res["success"] = 1;
        } else {
            $res["success"] = 0;
        }
    } else {
        $res["success"] = 0;
    }
}

echo json_encode($res);
mysqli_close($conn);
