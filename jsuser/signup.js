$(document).ready(function () {
    $(".btnSignUp").click(function () {
        var username = $(".txtUsername").val();
        var gmail = $(".txtGmail").val();
        var password = $(".txtPassword").val();
        var confirmPassword = $(".txtConfirmPassword").val();

        if (!username) {
            toastr.error('Vui lòng nhập tên người dùng!');
            $(".txtUsername").focus(); 
            return; 
        }

        if (!gmail) {
            toastr.error('Vui lòng nhập email!');
            $(".txtGmail").focus(); 
            return;
        }

        if (!password) {
            toastr.error('Vui lòng nhập mật khẩu!');
            $(".txtPassword").focus(); 
            return;
        }

        if (!confirmPassword) {
            toastr.error('Vui lòng xác nhận mật khẩu!');
            $(".txtConfirmPassword").focus(); 
            return;
        }

        if (password !== confirmPassword) {
            toastr.error('Mật khẩu và Xác nhận mật khẩu không đúng!');
            return;
        }

        var dataSend = {
            username: username,
            gmail: gmail,
            password: password,
            role: 1
        };
        
        var url = "http://localhost:1408/CosmeticsShop/php/api_insert_login.php";
        queryData_POST(url, dataSend, function (res) {
            if (res.success == 1) {
                Swal.fire({
                    title: 'Thành công!',
                    text: 'Tạo tài khoản thành công!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "login.html";
                    }
                });
            } else if (res.success == 2) {
                Swal.fire('Cảnh báo!', 'Tài khoản đã tồn tại!', 'warning');
            } else {
                Swal.fire('Lỗi!', 'Tạo tài khoản không thành công!', 'error');
            }
        });
    });
});
