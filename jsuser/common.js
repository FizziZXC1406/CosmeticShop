function queryData_GET(url, dataSend, callback) {
    $.ajax({
        type: 'GET',
        url: url,
        data: dataSend,
        async: true,
        dataType: 'json',
        success: function (res) {
            console.log("Request succeeded with response:", res);
            callback(res);
        },
    });
}

function queryData_POST(url, dataSend, callback) {
    $.ajax({
        type: 'POST',
        url: url,
        data: dataSend,
        async: true,
        dataType: 'json',
        success: callback
    });
}