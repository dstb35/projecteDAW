$(document).ready(function () {
    restaurantid = $('#title').data('restaurantid');

    $.ajax({
        type: "GET",
        // TODO quitar debug mode  path: /employee/{id}/index
        url: '/app_dev.php/employee/' + restaurantid + '/index',
        success: fill_employees,
        error: error
    });

    $(document).on('click', '#asignar', function () {
        orderid = $(this).data('orderid');
        console.log('Order para asignar '+orderid);
        employee = $('#select-'+orderid+' option:selected').val();
        console.log('Empleado para asignar '+employee);
        if ($.isNumeric(employee)) {
            $.ajax({
                type: "POST",
                //url: 'order/'+restaurantid+'/add', quitar debug mode
                url: '/app_dev.php/order/' + restaurantid + '/assign/' + orderid + '/' + employee,
                success : success,
                error: error
            });
        }else{
          alert('Selecciona una empleado');
        }
    })
})

function error(jqXHR, statusText, error) {
}

function fill_employees(data, statusText, jqXHR){
    employees = $('.employees');
    $.each(data.employees, function(i, val){
        employees.append($('<option></option>').val(val.employeeid).html(val.name));
    });
    employees.val('');
}

function success(data, statusText, jqXHR){
    console.log(data.assigned.employee);
    $('#span-'+data.assigned.orderid).text(data.assigned.employee)
}
