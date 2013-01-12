(function($) {

	$(function() {

		var open_sections = $.cookie('nav_groups');
		
		if (open_sections) {
			$('div[rel="'+open_sections+'"] .item').slideDown(600).removeClass('collapsed');
		} else {
			// show the first box with js to get around page jump
			$('.box .item:first').slideDown(600).removeClass('collapsed');			
		}
		
		// show and hide the sections
		$('.box .title').click(function(){
			window.scrollTo(0, 0);
			if ($(this).next('div.item').hasClass('collapsed')) {
				$('.box .item').slideUp(600).addClass('collapsed');
				$.cookie('nav_groups', $(this).parent('.box').attr('rel'), { expires: 1 });
				$(this).next('div.collapsed').slideDown(600).removeClass('collapsed');
			}
		});

		// load edit via ajax
		$('a.ajax').live('click', function(){
			// make sure we load it into the right one
			var id = $(this).attr('rel');
			if ($(this).hasClass('add')) {
				// if we're creating a new one remove the selected icon from link in the tree
				$('.group-'+ id +' #link-list a').removeClass('selected');
			}
			// Load the form
			$('div#link-details.group-'+ id +'').load($(this).attr('href'), '', function(){
				$('div#link-details.group-'+ id +'').fadeIn();
				// display the create/edit title in the header
				var title = $('#title-value-'+id).html();
				$('div.box .title h4.group-title-'+id).html(title);
				
				// Update Chosen
				mwi.chosen();
			});
			return false;
		});

		// submit create form via ajax
		$('#nav-create button:submit').live('click', function(e){
			e.preventDefault();
			var url = $('#nav-create').attr("action");
			
			$.post(url, $('#nav-create').serialize(), function(message){

				// if message is simply "success" then it's a go. Refresh!
				if (message == 'success') {
					window.location.href = window.location
				}
				else {
					//$('div#flash-message').remove();
					$('div#flash-message').prepend(message);
					// Fade in the notifications
					$("div#flash-message").fadeIn("slow");
					$("html, body").animate({ scrollTop: 0 }, 600);
				}
			});
		});

		// submit edit form via ajax
		$('#nav-edit button:submit').live('click', function(e){
			e.preventDefault();
			$.post(SITE_URL + ADM_URI + 'navigation/edit/' + $('input[name="link_id"]').val(), $('#nav-edit').serialize(), function(message){

				// if message is simply "success" then it's a go. Refresh!
				if (message == 'success') {
					window.location.href = window.location
				}
				else {
					$('.notification').remove();
					$('div#content-body').prepend(message);
					// Fade in the notifications
					$(".notification").fadeIn("slow");
				}
			});

		});

		// Pick a rule type, show the correct field
		$('input[name="link_type"]').live('change', function(){
			$(this).parents('ul').find('#navigation-' + $(this).val())

			// Show only the selected type
			.show().siblings().hide()

			// Reset values when switched
			.find('input:not([value="http://"]), select').val('');

		// Trigger default checked
		}).filter(':checked').change();

		// show link details
		$('#link-list li a').livequery('click', function()
		{
			var id = $(this).attr('rel');
			link_id = $(this).attr('alt');
			$('.group-'+ id +' #link-list a').removeClass('selected');
			$(this).addClass('selected');

			// Load the details box in
			$('div#link-details.group-'+ id +'').load(SITE_URL + ADM_URI + 'navigation/links/' + link_id, '', function(){
				$('div#link-details.group-'+ id +'').fadeIn();
			});
			// Remove the title from the form pane.
			$('div.box .title h4.group-title-'+id).html('');

			return false;
		});
		
		$('.box:visible ul.sortable').livequery(function(){
			$item_list		= $(this);
			$url			= ADM_URI + 'navigation/links/0';
			$cookie			= 'open_links';
			$data_callback	= function(event, ui) {
				// Grab the group id so we can update the right links
				return { 'group' : ui.item.parents('div.box').attr('rel') };
			}
			// $post_callback is available but not needed here
			
			// Get sortified
			mwi.sort_tree($item_list, $url, $cookie, $data_callback);
		});

		$('.nav-group-bar').tooltip();

	});

})(jQuery);