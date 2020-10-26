$(document).ready(function () {
    restaurantid = $('#title').data('restaurantid');
    console.log("document.URL : "+document.URL);
    console.log("document.location.href : "+document.location.href);
    console.log("document.location.origin : "+document.location.origin);
    console.log("document.location.hostname : "+document.location.hostname);
    console.log("document.location.host : "+document.location.host);
    console.log("document.location.pathname : "+document.location.pathname);

    $.ajax({
        type: "GET",
        // TODO quitar debug mode  path: /employee/{id}/index
        url: '/app_dev.php/employee/' + restaurantid + '/index',
        success: fill_employees,
        error: error
    });

    $(document).on('click', '#asignar', function () {
        orderid = $(this).data('orderid');
        console.log('Order para asignar ' + orderid);
        employee = $('#select-' + orderid + ' option:selected').val();
        console.log('Empleado para asignar ' + employee);
        if ($.isNumeric(employee)) {
            $.ajax({
                type: "POST",
                //url: 'order/'+restaurantid+'/add', quitar debug mode
                url: '/app_dev.php/order/' + restaurantid + '/assign/' + orderid + '/' + employee,
                success: success,
                error: error
            });
        } else {
            alert('Selecciona una empleado');
        }
    })

    $(document).on('click', '#cobrar', function () {
        orderid = $(this).data('orderid');
        console.log('Order para cobrar ' + orderid);
        $.ajax({
            type: "POST",
            //url: 'order/'+restaurantid+'/add', quitar debug mode
            url: '/app_dev.php/order/' + restaurantid + '/pay/' + orderid,
            success: paySuccess,
            error: error
        });
    })

    $(document).on('click', '#servir', function () {
        orderid = $(this).data('orderid');
        console.log('Order para servir ' + orderid);
        $.ajax({
            type: "POST",
            //url: 'order/'+restaurantid+'/add', quitar debug mode
            url: '/app_dev.php/order/' + restaurantid + '/serve/' + orderid,
            success: serveSuccess,
            error: error
        });
    })

    $(document).on('click', '#borrar', function () {
        orderid = $(this).data('orderid');
        console.log('Order para borrar ' + orderid);
        $.ajax({
            type: "POST",
            //url: 'order/'+restaurantid+'/add', quitar debug mode
            url: '/app_dev.php/order/' + restaurantid + '/remove/' + orderid,
            success: removeSuccess,
            error: error
        });
    })
})

function error(jqXHR, statusText, error) {
}

function fill_employees(data, statusText, jqXHR) {
    employees = $('.employees');
    $.each(data.employees, function (i, val) {
        employees.append($('<option></option>').val(val.employeeid).html(val.name));
    });
    employees.val('');
}

function success(data, statusText, jqXHR) {
    console.log(data.assigned.employee);
    $('#span-' + data.assigned.orderid).text('Empleado: ' + data.assigned.employee)
}

function paySuccess(data, statusText, jqXHR) {
    console.log(data.paid.message);
    console.log(data.paid.orderid);
    $('#paid-' + data.paid.orderid).text('Cobrado: ' + data.paid.message);
}

function serveSuccess(data, statusText, jqXHR) {
    console.log(data.served.message);
    console.log(data.served.orderid);
    $('#served-' + data.served.orderid).text('Servido: ' + data.served.message);
}

function removeSuccess(data, statusText, jqXHR) {
    console.log(data.removed.orderid);
    var url = $(location).attr("href");
    var patt = new RegExp('get$');
    if (patt.test(url)){
        //TODO Cambiar a url sin dev
        window.location.href = document.location.origin+"/app_dev.php/order/"+restaurantid+"/index";
    }else{
        $('#order-'+data.removed.orderid).remove();
    }
}
