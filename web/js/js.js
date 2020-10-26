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
            $('#content').prepend("<div id='found' class=\"alert alert-danger\">Producto no encontrado en la cesta</div>")
            setTimeout(function () {
                $('#found').remove();
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
        $('#cart').append("<li class='list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0'>" + val.title + ". Cantidad: " + val.quantity + "</li>")
        total += val.price * val.quantity;
    })
    $('#total').text(total + " €");
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
}
