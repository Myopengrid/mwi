jQuery(function($){
    var parents = 'form';
    $(parents +' div.ui-sortable').sortable({
        handle: 'span.move-handle',
        update: function() {
            $(parents +' div.ui-sortable div.control-group').removeClass('even');
            $(parents +' div.ui-sortable div.control-group:nth-child(even)').addClass('even');
            order = new Array();
            $(parents +' div.ui-sortable div.control-group label').each(function(){
                order.push( $(this).attr('for'));
            });
            order = order.join(',');

            $.post(SITE_URL + ADM_URI + 'splashscreen', {_method: 'PUT', csrf_token: "{{Session::token()}}", order: order });
        }
    });
});