$(document).ready(function () {
    orderlines = [];
    found = false;
    restaurantid = $('#enviar').data('restaurantid');
    tableid = $('#enviar').data('tableid');

    if (tableid == 0){
        $.ajax({
            type: "GET",
            // TODO quitar debug mode
            url: '/app_dev.php/table/' + restaurantid + '/index',
            data: {'restaurantid': restaurantid},
            success: fill_tables,
            error: error
        });
    }

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
        var tablesele = 0;
        if (tableid == 0){
            tablesele = $('#tables option:selected').val();
            console.log($('#tables option:selected').val());
        }else{
            tablesele = tableid;
        }
        if ($.isNumeric(tablesele)) {
            $.ajax({
                type: "POST",
                //url: 'order/'+restaurantid+'/add', quitar debug mode
                url: '/app_dev.php/order/' + restaurantid + '/add',
                data: {'orderlines': orderlines, 'tableid': tablesele},
                success: success,
                //dataType: 'json',
                error: error
            });
        }else{
          alert('Selecciona una mesa');
        }
    })

    $('#order-preview').on('click', function (e) {
        e.preventDefault();
        var carrito = $('.carrito')[0];

        $(carrito).hasClass('d-none') && $(carrito).removeClass('d-none');
    })

    $('#carrito-close').on('click', function (e) {
        $(this).parent().parent().addClass('d-none');
    })
});

function rellenar_cart() {
    var total = 0;
    $('#cart').empty();
    $.each(orderlines, function (i, val) {
        $('#cart').append(`<li class='list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0'>${val.quantity} x ${val.title}</li>`);
        total += val.price * val.quantity;
    });

    $('#total').text(total.toFixed(2) + " €");
    $('#total-sum').text(total.toFixed(2) + " €");
    if (orderlines.length > 0) {
        $('#enviar').show();
    } else {
        $('#enviar').hide();
    }
}

function success(data, statusText, jqXHR) {
    $('#cart').empty();
    $('#enviar').hide();
    orderlines = [];
    alert('Pedido realizado con nº: '+data.data.orderid);
    $('#response').text('Pedido realizado con nº: '+data.data.orderid);
}

function error(jqXHR, statusText, error) {
    console.log(error);
    alert(jqXHR.responseText);
}

function fill_tables(data, statusText, jqXHR){
    tables = $('#tables');
    $.each(data.tables, function(i, val){
        tables.append($('<option></option>').val(val.tableid).html(val.name));
    });
    tables.val('');
}
