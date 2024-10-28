$(document).ready(function () {
    $(".txtSearch").keyup(function (event) {
        if (event.which === 13) {
            var stringSearch = $(".txtSearch").val();
            showAllDataProductIndexPage(stringSearch);
        }
    });

    $(".btnSearch").click(function () {
        var stringSearch = $(".txtSearch").val();
    });

    $(".txtSearchByPrice").keyup(function (event) {
        if (event.which === 13) {
            var stringSearch = $(".txtSearchByPrice").val();
            var dau = $(".cbDau").val();
            showAllDataProductByPrice(stringSearch, dau);
        }
    });

    $(".btnSearchByPrice").click(function () {
        var stringSearch = $(".txtSearchByPrice").val();
        var dau = $(".cbDau").val();
        showAllDataProductByPrice(stringSearch, dau);
    });

    showAllDataProductIndexPage("");

    var getUsername = localStorage.getItem("Username");

    if (getUsername == undefined || getUsername == "" || getUsername == null) {
        $(".accountUsername").html(" ");
        $(".btnLogout").hide();
    } else {
        $(".btnSignUp").hide();
        $(".btnLogin").hide();
        $(".accountUsername").html('@' + getUsername);
    }

    $(".btnLogout").click(function () {
        localStorage.removeItem("rememberLogin");
        localStorage.removeItem("Username");
        localStorage.removeItem("Password");
        $(".btnSignUp").show();
        $(".btnLogin").show();
        $(".btnLogout").hide();
        $(".accountUsername").html("");
    });

    builds_Type();
    builds_NHOMMATHANG();
    builds_NHANHANG();

    $(".addListNHOMMATHANG").on('click', '.click-nhom', function () {
        var ma = $(this).attr("data-manhom");
        var vt = $(this).attr("data-vitri");


        var dataSend = {
            manhom: ma,
            vt: vt
        };

        var url = "http://localhost:1408/CosmeticsShop/php/api_get_mathang_theonhom.php?MANHOMMH=" + ma;

        queryData_GET(url, dataSend, function (res) {
            if (res && res.manhommh) {
                buildsHTML_NHOMMATHANG_VITRI(res, vt);
            } else {
                console.error("manhommh not found in response:", res);
            }
        });
    });

    $(".addLoaiSon").on('click', '.click-nhom', function (event) {
        var ma = $(this).attr("data-manhom");
        var vt = $(this).attr("data-vitri");
        var dataSend = {
            manhom: ma,
            vt: vt
        }
        event.preventDefault();

        $('html, body').animate({
            scrollTop: $("#showmathang").offset().top
        }, 500);

        var url = "http://localhost:1408/CosmeticsShop/php/api_get_mathang_theonhom.php?MANHOMMH=" + ma;

        queryData_GET(url, dataSend, function (res) {
            if (res && res.manhommh) {
                buildsHTML_SHOWMATHANG(res);
            } else {
                console.error("manhommh not found in response:", res);
            }
        });
    });

    $(".addItemMatHang").on('click', '.btnDelete', function () {
        var mamh = ($(this).attr("data-mamh"))

        var dataSend = {
            mamh: mamh
        };
        var url = "http://localhost:1408/CosmeticsShop/php/api_delete_mathang.php";

        queryData_POST(url, dataSend, function (res) {
            if (res.success == 1) {
                Swal.fire({
                    title: 'Xoá Thành công!',
                    text: 'Thực Hiện Xoá Thành Công!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                })
                showAllDataProductIndexPage("");
            } else {
                Swal.fire({
                    title: 'Xoá KHÔNG Thành công!',
                    text: 'Thực Hiện Xoá KHÔNG Thành Công!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                })
            }
        });
    });
});


function showAllDataProductByPrice(s, dau) {
    var dataSend = {
        search: s,
        dau: dau
    };

    var url = "http://localhost:1408/CosmeticsShop/php/api_get_all_mathang_by_price.php";

    queryData_GET(url, dataSend, function (res) {
        if (res && res.tatcamathang) {
            buildsHTML_SHOWALLMATHANG(res);
        } else {
            console.error("tatcamathang not found in response:", res);
        }
    });
}

function showAllDataProductIndexPage(s) {
    var dataSend = {
        search: s
    };

    var url = "http://localhost:1408/CosmeticsShop/php/api_get_all_mathang.php";

    queryData_GET(url, dataSend, function (res) {
        if (res && res.tatcamathang) {
            buildsHTML_SHOWALLMATHANG(res);
        } else {
            console.error("tatcamathang not found in response:", res);
        }
    });
}

function buildsHTML_SHOWALLMATHANG(res) {
    var data = res.tatcamathang;

    var html = '';

    var role = localStorage.getItem("Role");
    var remove = '';
    if (role == 0) {
        remove = '<i class="ri-delete-back-2-line"></i>';
    }

    for (var i = 0; i < data.length; i++) {
        var list = data[i];
        var imageSrc = '';
        var sl = '';
        if (list.HINHANH) {
            imageSrc = 'data:image/jpeg;base64,' + list.HINHANH;
        }
        if (list.TONGSOLUONG == 0) {
            sl = 'Hết hàng';
        } else {
            sl = list.TONGSOLUONG;
        }
        html += `<div class="col-lg-4 col-md-6 pb-1">
                <div class="cat-item d-flex flex-column border mb-4" style="justify-items: center;">
                    <a href="detail.html?mamh=${list.MAMH}" class="cat-img position-relative overflow-hidden mb-3">
                        <img class="img-fluid" src="${imageSrc}" alt="">
                    </a>
                    <div style="margin: 10px">
                        <h5 style="text-align: left; color: #884e4a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" class="m-0">${list.TENMH}
                        </h5>
                    </div>
                    <div style="margin: 10px">
                        <h5 style="text-align: left; color: #884e4a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" class="m-0">${list.GIA}
                        </h5>
                    </div>
                    <div style="flex-direction: column; margin: 10px 0 0 10px">
                        <a style="color: #41aea7" class="">#${list.TENNHOMMH}
                        </a>
                        <h6 style="color: #41aea7;" class="font-weight: 100 !important">Số lượng: ${sl}
                        </h6>
                        <div class="btnDelete" data-mamh = ${list.MAMH} style="width: 50px; height: 50px; cursor: pointer; display: flex; justify-content: center">
                            ${remove}
                        </div>
                    </div>
                </div>
            </div>`;
        $(".addItemMatHang").append(html)
    }
    $(".addItemMatHang").html(html);
}

function buildsHTML_SHOWMATHANG(res) {
    var data = res.manhommh;

    var html = '';
    for (var i = 0; i < data.length; i++) {
        var list = data[i];
        var imageSrc = '';
        if (list.HINHANH) {
            imageSrc = 'data:image/jpeg;base64,' + list.HINHANH;
        }
        html += `<div class="col-lg-4 col-md-6 pb-1">
                <div class="cat-item d-flex flex-column border mb-4" style="justify-items: center;">
                    <a href="" class="cat-img position-relative overflow-hidden mb-3">
                        <img class="img-fluid" src="${imageSrc}" alt="">
                    </a>
                    <h5 style="text-align: center;" class="font-weight-semi-bold m-0">${list.TENMH}
                    </h5>
                    
                </div>
            </div>`;
        $(".addItemMatHang").append(html)
    }
    $(".addItemMatHang").html(html);
}


function builds_NHOMMATHANG() {
    var dataSend = {
    }
    queryData_GET("http://localhost:1408/CosmeticsShop/php/api_get_nhom.php", dataSend, function (res) {
        buildsHTML_NHOMMATHANG(res);
    });
}

function buildsHTML_NHOMMATHANG(res) {
    var data = res.nhommathang;
    var html = '';
    var vitri = 1;
    for (var i = 0; i < data.length; i++) {
        var list = data[i];
        html += `<div class="nav-item dropdown " style="display: flex; background-color: #41aea7">
                    <a style="border-bottom: none !important; color: white" href="#" class="nav-link click-nhom" data-vitri = "${vitri}" data-manhom = "${list.MANHOMMH}" data-toggle="dropdown">${list.TENNHOMMH}
                        <i class="fa fa-angle-down float-right mt-1" style="margin-left: 10px; color: white"></i>
                    </a>
                    <div class="dropdown-menu position-absolute bg-secondary border-0 rounded-0 w-100 m-0 addMenuSub${vitri}">
                    </div>
                </div>`;
        vitri++;
    }
    $(".addListNHOMMATHANG").html(html);
}

function buildsHTML_NHOMMATHANG_VITRI(res) {
    var data = res.manhommh;

    var html = '';

    for (item in data) {
        var list = data[item];
        html += `<a href="detail.html?mamh=${list.MAMH}" class="dropdown-item" style="font-size: 13px; margin-left: 20px">${list.TENMH}</a>`;
    }
    $(".addMenuSub" + list.vt).html(html)
}

function builds_NHANHANG() {
    var dataSend = {
    }
    queryData_GET("http://localhost:1408/CosmeticsShop/php/api_get_nhom.php", dataSend, function (res) {
        buildsHTML_NHANHANG(res);
    });
}

function buildsHTML_NHANHANG(res) {
    var data = res.nhommathang;
    var html = '';
    var vt = 1;
    for (var item in data) {
        var list = data[item];
        var imageSrc = '';
        if (list.HINHANH) {
            imageSrc = 'data:image/jpeg;base64,' + list.HINHANH;
        }
        html += `<div class="brandInformation">
                    <a href="#showmathang" class="dropdown-item click-nhom" data-vitri="${vt}" data-manhom = "${list.MANHOMMH}">${list.TENNHOMMH}</a>
                </div>`;
        vt++;
    }
    $(".addLoaiSon").html(html);
}

function builds_Type() {
    var dataSend = {
    }
    queryData_GET("http://localhost:1408/CosmeticsShop/php/api_get_nhom.php", dataSend, function (res) {
        buildsHTML_Type(res);
    });

}

function buildsHTML_Type(res) {
    var data = res.nhommathang;
    var html = '';

    for (item in data) {
        var list = data[item];
        html += `<div class="nav-link click-nhom" data-manhom = ${list.MANHOMMH}>
                    <div class="dropdown-item">
                        <a href="#" style = "color: black">${list.TENNHOMMH}</a>
                    </div>
                </div>`;
    }
    $(".addType").html(html)
}
// function builds_DETAIL() {
//     var dataSend = {
//     }
//     queryData_GET("http://localhost:1408/CosmeticsShop/php/api_get_nhom.php", dataSend, function (res) {
//         buildsHTML_DETAIL(res);
//     });
// }

// function buildsHTML_DETAIL(res) {
//     var data = res.mathang;
//     var htmlHinhAnh = '';
//     var htmlTenSon = '';
//     var htmlGia = '';
//     var htmlDVT = '';
//     var htmlMoTa = '';
//     for (var i = 0; i < data.length; i++) {
//         var list = data[i];
//         var imageSrc = '';
//         if (list.HINHANH) {
//             imageSrc = 'data:image/jpeg;base64,' + list.HINHANH;
//             htmlHinhAnh += `<div class="containerIMG">
//                         <img src="${imageSrc}" alt="">
//                     </div>`;
//         }

//         if (list.TENMH) {
//             htmlTenSon += ` <label>${list.TENMH}</label>`
//         }

//         if (list.MOTA) {
//             htmlMoTa += `<p>${list.MOTA}</p>`
//         }

//         if (list.GIA) {
//             htmlGia += `${list.GIA}`
//         }

//         if (list.ĐVT) {
//             htmlDVT += `${list.ĐVT}`
//         }
//     }
//     var htmlGiaDVT = `<span>${htmlGia}</span>
//                             <span>${htmlDVT}</span>`
//     $(".addProductDetails").html(htmlHinhAnh);
//     $(".name").html(htmlTenSon);
//     $(".contentDescription").html(htmlMoTa);
//     $(".price").html(htmlGiaDVT);
// }

// MAUMATHANG - start
// function builds_MAUMATHANG() {
//     var dataSend = {
//     }
//     queryData_GET("http://localhost:1408/CosmeticsShop/php/api_get_nhom.php", dataSend, function (res) {
//         buildsHTML_MAUMATHANG(res);
//     });
// }

// function buildsHTML_MAUMATHANG(res) {
//     var data = res.maumathang;
//     var html = '';

//     for (var item in data) {
//         var list = data[item];
//         var imageSrc = '';
//         if (list.HINHANHMAU) {
//             imageSrc = 'data:image/jpeg;base64,' + list.HINHANHMAU;
//         }
//         html += `<label class="colorOption">
//                     <input type="radio" name="color" value="">
//                     <img src="${imageSrc}" alt="${list.TENMAU}">
//                     <h6 class="nameColor">${list.TENMAU}</h6>
//                  </label>`;
//     }
//     $(".containerColorRadioButton").html(html);
// }
// MAUMATHANG - end


// SANPHAMTUONGTU - start
// function builds_SANPHAMTUONGTU() {
//     var dataSend = {
//     }
//     queryData_GET("http://localhost:1408/CosmeticsShop/php/api_get_mathangtuongtu.php", dataSend, function (res) {
//         buildsHTML_SANPHAMTUONGTU(res);
//     });
// }

// function buildsHTML_SANPHAMTUONGTU(res) {

//     var data = res.tuongtu;
//     var html = '';

//     for (var item in data) {
//         var list = data[item];
//         var imageSrc = '';
//         if (list.HINHANH) {
//             imageSrc = 'data:image/jpeg;base64,' + list.HINHANH;
//         }
//         html += `<div class="product">
//                 <div class="cat-item">
//                     <a href="" class="position-relative overflow-hidden mb-3">
//                         <img class="img-fluid" src="${imageSrc}" alt="">
//                     </a>
//                     <div style="justify-content: center; display: flex;">
//                         <h6 class="" style = "margin: 10px">${list.TENMH}</h6>
//                     </div>
//                     <div style="justify-content: center; display: flex; margin-top: 20px;">
//                         <h6 class="newProductsPrice">${list.GIA} ${list.ĐVT}</h6>
//                     </div>
//                 </div>
//             </div>`;
//     }
//     $(".containerSimilarProducts").html(html);
// }
// SANPHAMTUONGTU - end
