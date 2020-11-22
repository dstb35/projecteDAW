$(document).ready(function () {
    var allergens = $('#appbundle_product_allergens');
    if (allergens.length > 0) {
        $(allergens).find('input').each(function(i, input) {
            $(input).wrap('<div class="form-check form-check-inline"></div>');
            $(input).addClass('form-check-input');
        });

        $(allergens).find('label').each(function(i, label) {
            $(label).addClass('form-check-label');
            $(label).prev().append(label);
        });
    }

    var submitButton = $('.productEdit #appbundle_product_Guardar');
    $(submitButton).parent().next().before(submitButton);
    $(submitButton).parent().find('div').remove();
});