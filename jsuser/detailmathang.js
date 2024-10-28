$(document).ready(function () {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const mamh = urlParams.get('mamh');

    if (mamh) {
        var url = "http://localhost:1408/CosmeticsShop/php/api_get_mathang_theomamathang.php?MAMH=" + mamh;

        queryData_GET(url, {}, function (res) {
            console.log("API Response: ", res);

            if (res && res.mamathang) {
                buildsHTML_DETAIL(res);
            } else {
                console.error("Không tìm thấy sản phẩm hiện tại trong response:", res);
            }

            if (res && res.mamathangtuongtu) {
                buildsHTML_SANPHAMTUONGTU(res);
            } else {
                console.error("Không tìm thấy Sản Phẩm Tương Tự trong response:", res);
            }

            if (res && res.maumathang) {
                buildsHTML_MAUMATHANG(res);
            } else {
                console.error("Không tìm thấy Màu Mặt Hàng trong response:", res);
            }
        });
    } else {
        console.error("Không có MAMH trong URL");
    }
});

function buildsHTML_DETAIL(res) {
    var data = res.mamathang;

    var htmlHinhAnh = '';
    var htmlTenSon = '';
    var htmlGia = '';
    var htmlDVT = '';
    var htmlMoTa = '';
    var htmlTenNhanHang = '';

    for (var i = 0; i < data.length; i++) {
        var list = data[i];
        var imageSrc = '';
        if (list.HINHANH) {
            imageSrc = 'data:image/jpeg;base64,' + list.HINHANH;
            htmlHinhAnh += `<div class="containerIMG">
                        <img src="${imageSrc}" alt="">
                    </div>`;
        }
        if (list.TENNHANHANG) {
            htmlTenNhanHang += `<i class="ri-arrow-right-s-line"></i>
                        <a href="">${list.TENNHANHANG}</a>`;
        }

        if (list.TENMH) {
            htmlTenSon += ` <label>${list.TENMH}</label>`
        }

        if (list.MOTA) {
            htmlMoTa += `<p>${list.MOTA}</p>`
        }

        if (list.GIA) {
            var formattedPrice = parseFloat(list.GIA).toLocaleString('vi-VN');
            htmlGia += `${formattedPrice}`
        }

        if (list.ĐVT) {
            htmlDVT += `${list.ĐVT}`
        }
    }

    var htmlGiaDVT = `  <span>${htmlGia}</span>
                        <span>${htmlDVT}</span>`

    $(".addProductDetails").html(htmlHinhAnh);
    $(".nameProduct").html(htmlTenSon);
    $(".nameBrand").html(htmlTenNhanHang);
    $(".contentDescription").html(htmlMoTa);
    $(".price").html(htmlGiaDVT);
}

function buildsHTML_MAUMATHANG(res) {
    var data = res.maumathang;
    var html = '';

    for (var item in data) {
        var list = data[item];
        var imageSrc = '';
        if (list.HINHANHMAU) {
            imageSrc = 'data:image/jpeg;base64,' + list.HINHANHMAU;
        }
        html += `<label class="colorOption">
                    <input type="radio" name="color" value="">
                    <img src="${imageSrc}" alt="${list.TENMAU}">
                    <h6 class="nameColor">${list.TENMAU}</h6>
                 </label>`;
    }
    $(".containerColorRadioButton").html(html);
}

function buildsHTML_SANPHAMTUONGTU(res) {
    var data = res.mamathangtuongtu;
    if (!data || data.length === 0) {
        console.log("Không có sản phẩm tương tự");
        return;
    }
    else {
        console.log("Chạy Thành Công Function buildsHTML_SANPHAMTUONGTU");
    }
    var data = res.mamathangtuongtu;
    var html = '';

    for (var item in data) {
        var list = data[item];
        var imageSrc = '';
        var formattedPrice = '';

        if (list.HINHANH) {
            imageSrc = 'data:image/jpeg;base64,' + list.HINHANH;
        }
        if (list.GIA) {
            formattedPrice = parseFloat(list.GIA).toLocaleString('vi-VN');
        }
        html += `<div class="product" style="height: 400px">
                    <div class="cat-item">
                        <a href="" class="position-relative overflow-hidden mb-3" style="display: flex; justify-content: center">
                            <img class="img-fluid" src="${imageSrc}" alt="">
                        </a>
                        <div style="justify-content: center; display: flex; margin-bottom: 30px">
                            <h6 title="${list.TENMH}" class="" style = "margin: 0 20px 0 20px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            ${list.TENMH}
                            </h6>
                        </div>
                        <div style="justify-content: center; display: flex;">
                            <h6 class="similarProductsPrice">${formattedPrice} ${list.ĐVT}</h6>
                        </div>
                    </div>
                </div>`;
    }
    $(".containerSimilarProducts").html(html);
}