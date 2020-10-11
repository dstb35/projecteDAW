$(document).ready(function () {
    orderlines = [];

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
        found = false;
        $.each(orderlines, function (i, val) {
            if (val.productid == orderline.productid) {
                orderlines[i].quantity -= 1;
                if (orderlines[i].quantity == 0){
                    found = i;
                }else{
                    found = true;
                }
                return false;
            }
        })
        console.log(found);
        if (found == false) {
            $('#title').append("<div class=\".alert-dimissible\">Producto no encontrado en la cesta</div>")
        }else if ($.isNumeric(found)){
            orderlines.splice(found, 1);
            console.log(borrado);
        }
        rellenar_cart();
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
}