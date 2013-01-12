
jQuery(function($){
    var parents = 'div.draggable-fields';
    $(parents +' ul').sortable({
        handle: 'span.move-handle',
        update: function() {
            $(parents +' ul li').removeClass('even');
            $(parents +' ul li:nth-child(even)').addClass('even');
            order = new Array();
            count = 1;
            $(parents +' li').each(function(){
                order.push( [ this.id, count] );
                count++;
            });
            order = order.join(',');

            $.post(SITE_URL + ADM_URI + 'email/order', {csrf_token: CSRF_TOKEN, order: order });
        }
    });
});


$(document).ready(function() {
  var protocol = $('#mail_protocol option:selected').val();
  show_protocol_fields(protocol);
});

$('#mail_protocol').live('change',function() {
    var select = $(this);
    var protocol = select.val();
    show_protocol_fields(protocol);
    $('div.control-group').removeClass('error');
    $('span.help-inline').hide();
});

function show_protocol_fields(protocol) {

    if(protocol == 'mail')
    {
        $('.conf-smtp').slideUp();
        $('.conf-sendmail').slideUp();
        $('.conf-mail').slideDown();
        $('.conf-multi').slideDown();
    }
    if(protocol == 'smtp')
    {
        $('.conf-mail').slideUp();
        $('.conf-sendmail').slideUp();
        $('.conf-multi').slideUp();
        $('.conf-smtp').slideDown();
    }
    if(protocol == 'sendmail')
    {
        $('.conf-mail').slideUp();
        $('.conf-smtp').slideUp();
        $('.conf-sendmail').slideDown();
        $('.conf-multi').slideDown();
    }
}