$(document).ready(function () {
    orderlines = [];
    found = false;
    restaurantid = $('#enviar').data('restaurantid');

    $.ajax({
        type: "GET",
        // TODO quitar debug mode
        url: '/app_dev.php/table/' + restaurantid + '/index',
        data: {'restaurantid': restaurantid},
        success: fill_tables,
        error: error
    });

    $(document).on('click', '#comprar', function () {
        orderline = {
            productid: $(this).data('id'),
            title: $(this).data('name'),
            quantity: 1,
            price: $(this).data('price')
        }
        found = false;
        $.each(orderlines, function (i, val) {
            if (val.productid == orderline.productid) {
                orderlines[i].quantity += 1;
                found = true;
                return false;
            }
        })
        if (!found) {
            orderlines.push(orderline);
        }
        rellenar_cart();
    })

    $(document).on('click', '#eliminar', function () {
        productid = $(this).data('id');
        found = false;
        $.each(orderlines, function (i, val) {
            if (val.productid == productid) {
                console.log(orderline);
                orderlines[i].quantity -= 1;
                if (orderlines[i].quantity == 0) {
                    orderlines.splice(i, 1);
                }
                found = true;
                return false;
            }
        })

        if (found == false) {
            $('#title').append("<div class=\"alert alert-danger\">Producto no encontrado en la cesta</div>")
            setTimeout(function () {
                $('#title').empty()
            }, 3000);
        }
        rellenar_cart();
    })

    $(document).on('click', '#enviar', function () {
        var table = $('#tables option:selected').val();
        if ($.isNumeric(table)) {
            $.ajax({
                type: "POST",
                //url: 'order/'+restaurantid+'/add', quitar debug mode
                url: '/app_dev.php/order/' + restaurantid + '/add',
                data: {'orderlines': orderlines, 'tableid': table},
                success: success,
                //dataType: 'json',
                error: error
            });
        }else{
          alert('Selecciona una mesa');
        }
    })
})

function rellenar_cart() {
    var total = 0;
    $('#cart').empty();
    $.each(orderlines, function (i, val) {
        $('#cart').append("<li>" + val.title + "Cantidad: " + val.quantity + "</li>")
        total += val.price * val.quantity;
    })
    $('#total').html(total + " €");
    if (orderlines.length > 0) {
        $('#enviar').show();
    } else {
        $('#enviar').hide();
    }
}

function success(data, statusText, jqXHR) {
    console.log(data);
}

function error(jqXHR, statusText, error) {
    console.log(error, statusText);
}

function fill_tables(data, statusText, jqXHR){
    console.log(data);
    tables = $('#tables');
    $.each(data.tables, function(i, val){
        tables.append($('<option></option>').val(val.tableid).html(val.name));
    });
    tables.val('');
    /*var options ="";
    for(i=0; i<data.tables.length; i++) {
        $.each(data.results, function(i, val){
        tables.append($('<option></option>').val(data[i].tableid).html(data[i].name));
        tables.append($('<option value='+data[i].tableid+'>'+data[i].name+'</option>'));
        options += '<option value='+data.tables[i].tableid+'>' + data.tables[i].name + '</option>';
        console.log(data.tables[i].tableid + ': ' + data.tables[i].name);
        options += `<option value=${data[i].id}>${data[i].name_rus}</option>`;//<--string
        interpolation
        });
    }
    $('#tables').append(options);
    $(select).append(options);*/
}
