$(document).ready(function () {
    orderlines = [];
    found = false;

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
        id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: '/order/'+id+'/add',
            data: orderlines,
            success: success,
            dataType: datatype
        });
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