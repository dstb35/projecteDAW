$(document).ready(function () {
    $(document).on('click', '#print', function () {
        tableid = tableid = $('#qr').data('tableid');
        document.title = "Mesa:"+tableid;
        $('body').css('visibility', 'hidden');
        $('#qr').css('visibility', 'visible');
        window.print();
        $('body').css('visibility', 'visible');
    })
});