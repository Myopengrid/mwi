(function($) {
	$(function() {

		// set values for mwi.sort_tree (we'll use them below also)
		$item_list	= $('ul.sortable');
		$url		=  ADM_URI + 'pages/0';
		$cookie		= 'open_pages';

		// store some common elements
		$details 	= $('div#page-details');
		$details_id	= $('div#page-details #page-id');

		// show the page details pane
		$item_list.find('li a').live('click', function(e) {
			e.preventDefault();

			$a = $(this);

			page_id 	= $a.attr('rel');
			page_title 	= $a.text();
			$('#page-list a').removeClass('selected');
			$a.addClass('selected');

			// Load the details box in
			
			//Create object so the call will be a POST
			// var data = {page_id: page_id, csrf_token: CSRF_TOKEN };
			// $details.load(SITE_URL + ADM_URI + 'pages/ajax_page_details/', data, function(){
			// 	refresh_sticky_page_details(true);
			// });
			// 
			
			$details.load(SITE_URL + ADM_URI + 'pages/' + page_id, function(){
				refresh_sticky_page_details(true);
			});

			$details.parent().prev('section.title').html( $('<h4 />').text(page_title) );

			// return false to keep the list from toggling when link is clicked
			return false;
		});

		// retrieve the ids of root pages so we can POST them along
		data_callback = function(even, ui) {
			// In the pages module we get a list of root pages
			root_pages = [];
			// grab an array of root page ids
			$('ul.sortable').children('li').each(function(){
				root_pages.push($(this).attr('id').replace('page_', ''));
			});
			return { 'root_pages' : root_pages };
		}

		// the "post sort" callback
		post_sort_callback = function() {

			$details 	= $('div#page-details');
			$details_id	= $('div#page-details #page-id');

			// refresh pane if it exists
			if($details_id.val() > 0)
			{
				// Create object so the call will be a POST
				// var data = {page_id: $details_id.val(), csrf_token: CSRF_TOKEN};
				// $details.load(SITE_URL + ADM_URI + 'pages/ajax_page_details/', data);

				$details.load(SITE_URL + ADM_URI + 'pages/' + $details_id.val());
			}
		}

		// And off we go
		mwi.sort_tree($item_list, $url, $cookie, data_callback, post_sort_callback);

		 function refresh_sticky_page_details(reset) {
			 var els = $('.scroll-follow');
			if ( reset === true ) {
				els.stickyScroll('reset');
			}
			els.stickyScroll({ topBoundary: 170, bottomBoundary: 110, minimumWidth: 770});
		}
		refresh_sticky_page_details();
	});
})(jQuery);