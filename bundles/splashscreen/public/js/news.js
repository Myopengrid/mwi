$(function(){
    $('body').on('click', '.pagination a', function(e){
        e.preventDefault();
        url = $(this).attr('href');
        $.get(url, "", function(data, response, xhr) {
                    
            var ct      = xhr.getResponseHeader('content-type') || '',
                html    = '';

            if (ct.indexOf('application/json') > -1 && typeof data == 'object') {
                
                // json
                //html = 'html' in data ? data.html : '';
            }
            else {
                $('#flash-news-container').html(data);
            }
        });
    });
});
