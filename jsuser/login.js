$(document).ready(function () {
    $(".btnLogin").click(function () {
        var username = $(".txtUsername").val();
        var password = $(".txtPassword").val();

        if (!username) {
            toastr.error('Vui lòng nhập tên người dùng!');
            $(".txtUsername").focus();
            return;
        }

        if (!password) {
            toastr.error('Vui lòng nhập mật khẩu!');
            $(".txtPassword").focus();
            return;
        }

        var dataSend = {
            username: username,
            password: password
        };

        var url = "http://localhost:1408/CosmeticsShop/php/api_login.php";
        queryData_POST(url, dataSend, function (res) {
            if (res.success == 1) {
                if ($(".rememberPassword").is(':checked')) {
                    localStorage.setItem("rememberLogin", true);
                    localStorage.setItem("Username", username);
                    localStorage.setItem("Password", password);
                    localStorage.setItem("Role", res.user_info.role);
                } else {
                    localStorage.setItem("Username", username);
                    localStorage.removeItem("rememberLogin");
                    localStorage.setItem("Role", res.user_info.role);
                }
                Swal.fire({
                    title: 'Thành công!',
                    text: 'Đăng nhập thành công!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(".txtUsername").val('');
                        $(".txtPassword").val('');
                        window.location.href = "index.html";
                    }
                });
            } else {
                Swal.fire('Lỗi!', 'Đăng nhập không thành công!', 'error');
            }
        });
    });
});
