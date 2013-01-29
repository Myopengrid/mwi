$(function(){

    mwi.filter = {
        $content        : $('#filter-content'),
        // filter form object
        $filter_form    : $('#filters form'),

        //lets get the current module,  we will need to know where to post the search criteria
        f_module        : $('input[name="f_module"]').val(),

        /**
         * Constructor
         */
        init: function(){

            $('a.cancel').button();

            //listener for select elements
            $('select', mwi.filter.$filter_form).on('change', function(){

                //build the form data
                form_data = mwi.filter.$filter_form.serialize();

                //fire the query
                mwi.filter.do_filter(mwi.filter.f_module, form_data);
            });

            //listener for keywords
            $('input[type="text"]', mwi.filter.$filter_form).on('keyup', $.debounce(500, function(){
                //build the form data
                form_data = mwi.filter.$filter_form.serialize();

                mwi.filter.do_filter(mwi.filter.f_module, form_data);
            
            }));
    
            //listener for pagination
            $('body').on('click', '.pagination a', function(e){
                e.preventDefault();
                url = $(this).attr('href');
                form_data = mwi.filter.$filter_form.serialize();
                mwi.filter.do_filter(mwi.filter.f_module, form_data, url);
            });
            
            //clear filters
            $('a.cancel', mwi.filter.$filter_form).click(function() {
            
                    //reset the defaults
                    //$('select', filter_form).children('option:first').addAttribute('selected', 'selected');
                    $('select', mwi.filter.$filter_form).val('0');
                    
                    //clear text inputs
                    $('input[type="text"]').val('');
            
                    //build the form data
                    form_data = mwi.filter.$filter_form.serialize();
            
                    mwi.filter.do_filter(mwi.filter.f_module, form_data);
            });
            
            //prevent default form submission
            mwi.filter.$filter_form.submit(function(e){
                e.preventDefault(); 
            });

            // trigger an event to submit immediately after page load
            mwi.filter.$filter_form.find('select').first().trigger('change');
        },
    
        //launch the query based on module
        do_filter: function(module, form_data, url){
            form_action = mwi.filter.$filter_form.attr('action');
            
            post_url    = form_action ? form_action : SITE_URL + ADM_URI + module;

            var method  = mwi.filter.$filter_form.attr('method');

            if (typeof url !== 'undefined'){
                post_url = url;
            }

            mwi.clear_notifications();

            mwi.filter.$content.fadeOut('fast', function(){
                //send the request to the server
                
                $.ajax({
                    url     : url,
                    type    : method,
                    data    : form_data,
                    success : function(data, response, xhr) {
                        
                        var ct      = xhr.getResponseHeader('content-type') || '',
                            html    = '';

                        if (ct.indexOf('application/json') > -1 && typeof data == 'object') {
                            
                            html = 'html' in data ? data.html : '';

                            mwi.filter.handler_response_json(data);
                        }
                        else {
                            html = data;
                        }

                        //success stuff here
                        mwi.chosen();
                        mwi.filter.$content.html(html).fadeIn('fast');
                    }
                });
            });
        },

        handler_response_json: function(json)
        {
            if ('update_filter_field' in json && typeof json.update_filter_field == 'object')
            {
                $.each(json.update_filter_field, mwi.filter.update_filter_field);
            }
        },

        update_filter_field: function(field, data)
        {
            var $field = mwi.filter.$filter_form.find('[name='+field+']');

            if ($field.is('select'))
            {
                if (typeof data == 'object')
                {
                    if ('options' in data)
                    {
                        var selected, value;

                        selected = $field.val();
                        $field.children('option').remove();

                        for (value in data.options)
                        {
                            $field.append('<option value="' + value + '"' + (value == selected ? ' selected="selected"': '') + '>' + data.options[value] + '</option>');
                        }
                    }
                }
            }
        }
    };

    mwi.filter.init();

});