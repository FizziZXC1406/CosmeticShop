<?php
require_once("server.php"); // Kết nối từ file server.php

$error_message = ""; // Khởi tạo biến thông báo lỗi

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error_message = "Username và password không được để trống!";
    } else {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // In ra giá trị để kiểm tra
            echo "Mật khẩu nhập vào: " . $password . "<br>";
            echo "Mật khẩu trong DB: " . $row['password'] . "<br>";

            if (password_verify($password, $row['password'])) {
                header("Location: index.html");
                exit();
            } else {
                $error_message = "Sai mật khẩu!";
            }
        } else {
            $error_message = "Sai username hoặc password!";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!-- HTML phần của mã không thay đổi -->


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>H & T Shop - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <div class="login-container">
        <div class="col-left">
            <div class="welcome">Welcome To H & T Shop</div>
            <form action="login.php" method="POST">
                <div class="input-group">
                    <label>Liên Hệ</label>
                    <input type="text" name="username" class="username-information" placeholder="Enter your username">
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" class="password-information" placeholder="Enter your password">
                </div>
                <div class="forgotPassword-container">
                    <a href="">Forgot Password</a>
                </div>
                <button type="submit" class="login-btn">Login</button>
                <button class="google-btn" type="button">
                    <i class="ri-google-fill" style="margin-right: 8px;"></i>
                    Login with Google
                </button>
                <div class="signupForm-link">
                    <h6 class="question">You Don't Have An Account?</h6> <a href="Register.html">Sign Up</a>
                </div>
            </form>            
            <?php
            // Hiển thị thông báo lỗi nếu có
            if (!empty($error_message)) {
                echo "<div class='error-message'>$error_message</div>";
            }
            ?>
        </div>
        <div class="col-right">
            <div class="imagebg">
                <img src="../imgBackground/merzy-bg1.png" alt="merzy-bg1">
                <img src="../imgBackground/merzy-bg2.png" alt="merzy-bg2">
                <img src="../imgBackground/merzy-bg3.png" alt="merzy-bg3">
            </div>
        </div>
    </div>
</body>

</html>
