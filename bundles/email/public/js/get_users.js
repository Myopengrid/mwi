$(document).ready(function() {

    //should we ask for subject and body?
    // if this is a return from a failed post
    // and the templare was selected we should 
    // not ask for subject or body
    var select = $('#template');
    var newVal = select.val();
    if(newVal == 0)
    {
        $('.custom-email').slideDown();
    }
    else
    {
        $('.custom-email').slideUp();
    }

    // Lest try to set some fields when the document
    // just finished to load
    var status = $('select[name=status]').val();
    var group = $('select[name=group]').val();

    $.post(BASE_URL + ADM_URI + "email/get_users", { csrf_token: CSRF_TOKEN, status: status, group: group }, function(data) {

        newOptions = jQuery.parseJSON(data);
        var el = $("#only_emails");
        el.empty(); // remove old options

        $.each(newOptions, function(key, value) {
            
            // if this is a failed post callback
            // lets check if the user was selected
            // and re-add to the selected users
            var selected = '';
            // jQuery.inArray returns the first index that matches 
            // the item you searched for or -1 if it is not found
            if($.inArray(key, OLD_POST_EMAILS) > -1) selected = 'selected="selected"';


            el.append($("<option " + selected + " ></option>").attr("value", key).text(value));
        });

        el.trigger("liszt:updated");
    });
    

    // some of the filters were changed
    // lets update our values
    var selects = $('.chzn-filters');
    selects.chosen().change(function() {

        var status = $('select[name=status]').val();
        var group = $('select[name=group]').val();

        $.post(BASE_URL + ADM_URI + "email/get_users", { csrf_token: CSRF_TOKEN, status: status, group: group }, function(data) {

            newOptions = jQuery.parseJSON(data);
            var el = $("#only_emails");
            el.empty(); // remove old options

            $.each(newOptions, function(key, value) {
                el.append($("<option></option>").attr("value", key).text(value));
            });

            el.trigger("liszt:updated");
        });
    });
});

$('#template').live('change',function() {
    var select = $(this);
    var newVal = select.val();
    if(newVal == 0)
    {
        $('.custom-email').slideDown();
    }
    else
    {
        $('.custom-email').slideUp();
    }
});