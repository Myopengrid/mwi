(function($) {
	$(function(){

		// Generate a slug from the title
		mwi.generate_slug('input[name="title"]', 'input[name="slug"]');

		// needed so that Keywords can return empty JSON
		$.ajaxSetup({
			allowEmpty: true
		});

		$('#meta_keywords').tagsInput({
			autocomplete_url: BASE_URL + ADM_URI + 'settings/keywords/autocomplete/'
		});
		
		// Hide the huge textarea
		$('#page-chunks').on('mousedown', '.sort-handle', function(e){
			$('.chunky').hide();
		});

		$('#page-chunks').on('mouseup', '.sort-handle', function(e){
			$('.chunky').show();
		});
		
		// Attach sortable to the page chunks ul
		$("#page-chunks").sortable({
			opacity:0.3,
			handle: ".sort-handle",
			placeholder: 'sort-placeholder',
			forcePlaceholderSize: true,
			items: 'li',
			cursor:"move",
			start: function () {
				$('.wysiwyg-advanced, .wysiwyg-simple').each(function() {
					$(this).ckeditorGet().destroy();
				});
			},
			stop: function(ev,ui){
				$('.chunky').show();
				mwi.init_ckeditor();
			}
		});
		
		// add another page chunk
		$('a.add-chunk').live('click', function(e){
			e.preventDefault();

			// The date in hexdec
			key = Number(new Date()).toString(16).substr(-5, 5);

			$('#page-chunks').append('<div class="control-group"><li class="page-chunk">' +
				'' +
          		'<div class="controls">' +
          		'<div style="width:90%; margin:10px; vertical-align:text-middle;" class="wrapper">' +
				'<input style="margin-right:5px; margin-bottom: 15px;" type="text" name="chunk_slug[' + key + ']" value="' + key + '"/>' +
				'<input placeholder="class" style="margin-right:5px; margin-bottom: 15px;" type="text" name="chunk_class[' + key + ']" />' +
				''+
				'<select name="chunk_type[' + key + ']">' +
				'<option value="html">html</option>' +
				'<option value="markdown">markdown</option>' +
				'<option value="wysiwyg-simple">wysiwyg-simple</option>' +
				'<option selected="selected" value="wysiwyg-advanced">wysiwyg-advanced</option>' +
				'</select>' +
				
				'<a style="margin-right:5px;" href="javascript:void(0)" class="remove-chunk btn red">Remove</a>' +
				'<span class="sort-handle"></span>' +
				'</div>' +
				'' +
				'<span class="chunky" ><textarea  id="' + key + '" class="pages wysiwyg-advanced" rows="20" style="width:100%" name="chunk_body[' + key + ']"></textarea>' +
				'</span></div></li></div>');


			// initialize the editor using the view from fragments/wysiwyg.php
			mwi.init_ckeditor();
			$("#page-chunks").sortable("refresh");

			// Update Chosen
			mwi.chosen();
		});

		$('a.remove-chunk').live('click', function(e) {
			e.preventDefault();

			var removemsg = $(this).attr('title');

			if (confirm(removemsg || mwi.lang.dialog_message))
			{
				$(this).closest('li.page-chunk').slideUp('slow', function(){ $(this).remove(); });
				if ($('#page-content').find('li.page-chunk').length < 2) {
				}
			}
		});

		$('select[name^=chunk_type]').live('change', function() {
			chunk = $(this).closest('li.page-chunk');
			textarea = $('textarea', chunk);

			// Destroy existing WYSIWYG instance
			if (textarea.hasClass('wysiwyg-simple') || textarea.hasClass('wysiwyg-advanced'))
			{
				textarea.removeClass('wysiwyg-simple');
				textarea.removeClass('wysiwyg-advanced');

				var instance = CKEDITOR.instances[textarea.attr('id')];
				instance && instance.destroy();
			}

			// Set up the new instance
			textarea.addClass(this.value);

			mwi.init_ckeditor();
		});

		$('select[name=editor-selector]').live('change', function() {
			//chunk = $(this).closest('li.page-chunk');
			textarea = $('.edit-area');//$('textarea', chunk);
			//console.log('here');
			// Destroy existing WYSIWYG instance
			if (textarea.hasClass('wysiwyg-simple') || textarea.hasClass('wysiwyg-advanced'))
			{
				textarea.removeClass('wysiwyg-simple');
				textarea.removeClass('wysiwyg-advanced');

				var instance = CKEDITOR.instances[textarea.attr('id')];
				instance && instance.destroy();
			}

			// Set up the new instance
			textarea.addClass(this.value);

			mwi.init_ckeditor();
		});

		// $('#meta_keywords').tagit({
	 //        availableTags: keywords,
	 //        singleField: true,
	 //        caseSensitive: false,
	 //        singleFieldNode: $('#meta_keywords'),
	 //        allowSpaces: false
	 //    });

	});

})(jQuery);