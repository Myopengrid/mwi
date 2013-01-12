jQuery(function($) {
$('form input[name="name"]').keyup($.debounce(300, function(){
    var slug_input = $('input[name="slug"]');
    $.post(SITE_URL + ADM_URI + 'groups/check_slug', { 
            csrf_token : CSRF_TOKEN, 
            title : $(this).val() 
        },
        function(new_slug) {
            var slug = new_slug;
            var slugarr = slug.split("::");
            if(slugarr[1] == "error") {
                $('form input[name="slug"]').parent().append("<span style=\"margin-left:10px; color: #B94A48;\" class=\"live help-inline\">This short name is already taken.</span>");

                $('form input[name="slug"]').parent().parent('.control-group').addClass("error");

                slug_input.val( slugarr[2] );
            }
            else {
                $('.live').remove();
                slug_input.val( slug );
            }
        });
    }));
});   