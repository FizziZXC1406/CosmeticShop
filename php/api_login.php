<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With, Authorization, Content-Type');
header('Access-Control-Max-Age: 86400');

if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
    exit();
}

require_once("server.php");

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = sha1($_POST['password']);

    $stmt = $conn->prepare("SELECT username, gmail, password, role FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $res["success"] = 1;
        $res["message"] = "Đăng nhập thành công!";
        $res["user_info"] = [
            "username" => $user['username'],
            "gmail" => $user['gmail'],
            "password" => $user['password'],
            "role" => $user['role']
        ];
    } else {
        $res["success"] = 0;
        $res["message"] = "Sai tên người dùng hoặc mật khẩu!";
    }

    $stmt->close();
} else {
    $res["success"] = 0;
    $res["message"] = "Thiếu thông tin đăng nhập!";
}

echo json_encode($res);
mysqli_close($conn);
