/**
 * Mwo object
 *
 */

// It may already be defined in metadata partial
if (typeof(mwi) == 'undefined') {
  //var mwi = {};
  var mwi = { 'lang' : {} };
}

jQuery(function($) {


  mwi.foreign_characters      = [{"search":"\/\u00e4|\u00e6|\u01fd\/","replace":"ae"},{"search":"\/\u00f6|\u0153\/","replace":"oe"},{"search":"\/\u00fc\/","replace":"ue"},{"search":"\/\u00c4\/","replace":"Ae"},{"search":"\/\u00dc\/","replace":"Ue"},{"search":"\/\u00d6\/","replace":"Oe"},{"search":"\/\u00c0|\u00c1|\u00c2|\u00c3|\u00c4|\u00c5|\u01fa|\u0100|\u0102|\u0104|\u01cd|\u0391|\u0386\/","replace":"A"},{"search":"\/\u00e0|\u00e1|\u00e2|\u00e3|\u00e5|\u01fb|\u0101|\u0103|\u0105|\u01ce|\u00aa|\u03b1|\u03ac\/","replace":"a"},{"search":"\/\u00c7|\u0106|\u0108|\u010a|\u010c\/","replace":"C"},{"search":"\/\u00e7|\u0107|\u0109|\u010b|\u010d\/","replace":"c"},{"search":"\/\u00d0|\u010e|\u0110|\u0394\/","replace":"D"},{"search":"\/\u00f0|\u010f|\u0111|\u03b4\/","replace":"d"},{"search":"\/\u00c8|\u00c9|\u00ca|\u00cb|\u0112|\u0114|\u0116|\u0118|\u011a|\u0395|\u0388\/","replace":"E"},{"search":"\/\u00e8|\u00e9|\u00ea|\u00eb|\u0113|\u0115|\u0117|\u0119|\u011b|\u03ad|\u03b5\/","replace":"e"},{"search":"\/\u011c|\u011e|\u0120|\u0122|\u0393\/","replace":"G"},{"search":"\/\u011d|\u011f|\u0121|\u0123|\u03b3\/","replace":"g"},{"search":"\/\u0124|\u0126\/","replace":"H"},{"search":"\/\u0125|\u0127\/","replace":"h"},{"search":"\/\u00cc|\u00cd|\u00ce|\u00cf|\u0128|\u012a|\u012c|\u01cf|\u012e|\u0130|\u0397|\u0389|\u038a|\u0399|\u03aa\/","replace":"I"},{"search":"\/\u00ec|\u00ed|\u00ee|\u00ef|\u0129|\u012b|\u012d|\u01d0|\u012f|\u0131|\u03b7|\u03ae|\u03af|\u03b9|\u03ca\/","replace":"i"},{"search":"\/\u0134\/","replace":"J"},{"search":"\/\u0135\/","replace":"j"},{"search":"\/\u0136|\u039a\/","replace":"K"},{"search":"\/\u0137|\u03ba\/","replace":"k"},{"search":"\/\u0139|\u013b|\u013d|\u013f|\u0141|\u039b\/","replace":"L"},{"search":"\/\u013a|\u013c|\u013e|\u0140|\u0142|\u03bb\/","replace":"l"},{"search":"\/\u00d1|\u0143|\u0145|\u0147|\u039d\/","replace":"N"},{"search":"\/\u00f1|\u0144|\u0146|\u0148|\u0149|\u03bd\/","replace":"n"},{"search":"\/\u00d2|\u00d3|\u00d4|\u00d5|\u014c|\u014e|\u01d1|\u0150|\u01a0|\u00d8|\u01fe|\u039f|\u038c|\u03a9|\u038f\/","replace":"O"},{"search":"\/\u00f2|\u00f3|\u00f4|\u00f5|\u014d|\u014f|\u01d2|\u0151|\u01a1|\u00f8|\u01ff|\u00ba|\u03bf|\u03cc|\u03c9|\u03ce\/","replace":"o"},{"search":"\/\u0154|\u0156|\u0158|\u03a1\/","replace":"R"},{"search":"\/\u0155|\u0157|\u0159|\u03c1\/","replace":"r"},{"search":"\/\u015a|\u015c|\u015e|\u0160|\u03a3\/","replace":"S"},{"search":"\/\u015b|\u015d|\u015f|\u0161|\u017f|\u03c3|\u03c2\/","replace":"s"},{"search":"\/\u0162|\u0164|\u0166\u03a4\/","replace":"T"},{"search":"\/\u0163|\u0165|\u0167|\u03c4\/","replace":"t"},{"search":"\/\u00d9|\u00da|\u00db|\u0168|\u016a|\u016c|\u016e|\u0170|\u0172|\u01af|\u01d3|\u01d5|\u01d7|\u01d9|\u01db\/","replace":"U"},{"search":"\/\u00f9|\u00fa|\u00fb|\u0169|\u016b|\u016d|\u016f|\u0171|\u0173|\u01b0|\u01d4|\u01d6|\u01d8|\u01da|\u01dc|\u03c5|\u03cd|\u03cb\/","replace":"u"},{"search":"\/\u00dd|\u0178|\u0176|\u03a5|\u038e|\u03ab\/","replace":"Y"},{"search":"\/\u00fd|\u00ff|\u0177\/","replace":"y"},{"search":"\/\u0174\/","replace":"W"},{"search":"\/\u0175\/","replace":"w"},{"search":"\/\u0179|\u017b|\u017d|\u0396\/","replace":"Z"},{"search":"\/\u017a|\u017c|\u017e|\u03b6\/","replace":"z"},{"search":"\/\u00c6|\u01fc\/","replace":"AE"},{"search":"\/\u00df\/","replace":"ss"},{"search":"\/\u0132\/","replace":"IJ"},{"search":"\/\u0133\/","replace":"ij"},{"search":"\/\u0152\/","replace":"OE"},{"search":"\/\u0192\/","replace":"f"},{"search":"\/\u03b8\/","replace":"th"},{"search":"\/\u03c7\/","replace":"x"},{"search":"\/\u03c6\/","replace":"f"},{"search":"\/\u03be\/","replace":"ks"},{"search":"\/\u03c0\/","replace":"p"},{"search":"\/\u03b2\/","replace":"v"},{"search":"\/\u03bc\/","replace":"m"},{"search":"\/\u03c8\/","replace":"ps"}];

  // Set up an object for caching things
  mwi.cache = {
    // set this up for the slug generator
    url_titles  : {}
  }

  /**
   * Overload the json converter to avoid error when json is null or empty.
   */
  $.ajaxSetup({
    //allowEmpty: true,
    converters: {
      'text json': function(text) {
        var json = jQuery.parseJSON(text);
        if (!jQuery.ajaxSettings.allowEmpty == true && (json == null || jQuery.isEmptyObject(json)))
        {
          jQuery.error('The server is not responding correctly, please try again later.');
        }
        return json;
      }
    },
    data: {
      csrf_token: CSRF_TOKEN,
    }
  });

  /**
   * This initializes all JS goodness
   */
  mwi.init = function() {

    var current_module = $('#page-header h2 a').text();

    // Select menu for smaller screens
    $("<select />").appendTo("nav#primary");

    // Create default option "Menu"
    $("<option />", {
        "selected": "selected",
        "value"   : "",
        "text"    : "Menu"
    }).appendTo("nav#primary select");

    // Populate dropdown with menu items
    $("nav#primary a").each(function() {
      var el = $(this);
      $("<option />", {
          "value"   : el.attr("href"),
          "text"    : el.text()
      }).appendTo("nav#primary select");
    });

    $("nav#primary select").change(function() {
        window.location = $(this).find("option:selected").val();
    });

    $('.topbar ul li:not(#dashboard-link)').hoverIntent({
      sensitivity: 7,
      interval: 75,
      over: function(){ $(this).find('ul:first:hidden').css({visibility: "visible", display: "none"}).slideDown(400) },
      timeout: 0,
      out: function(){ $(this).parent().find('ul').slideUp(400) }
    });

    // Add class to dropdowns for styling
    $(".topbar ul li:has(ul)").children("a").addClass("menu");

    // Add the close link to all alert boxes
    $('.alert').livequery(function(){
      $(this).prepend('<a href="#" class="close">x</a>');
    });

    // Close the notifications when the close link is clicked
    $('a.close').live('click', function(e){
      e.preventDefault();
      $(this).fadeTo(200, 0); // This is a hack so that the close link fades out in IE
      $(this).parent().fadeTo(200, 0);
      $(this).parent().slideUp(400, function(){
        $(window).trigger('notification-closed');
        $(this).remove();
      });
    });

    $("#datepicker").datepicker({dateFormat: 'yy-mm-dd'});

    // Fade in the notifications
    $('.alert').livequery(function(){
      $(this).fadeIn('slow', function(){
        $(window).trigger('notification-complete');
      });
    });

    // Check all checkboxes in container table or grid
    $(".check-all").live('click', function () {
      var check_all   = $(this),
        all_checkbox  = $(this).is('.grid-check-all')
          ? $(this).parents(".list-items").find(".grid input[type='checkbox']")
          : $(this).parents("table").find("tbody input[type='checkbox']");

      all_checkbox.each(function () {
        if (check_all.is(":checked") && ! $(this).is(':checked'))
        {
          $(this).click();
        }
        else if ( ! check_all.is(":checked") && $(this).is(':checked'))
        {
          $(this).click();
        }
      });

      // Check all?
      $(".table_action_buttons .btn").prop('disabled', false);
    });

    // Table action buttons start out as disabled
    $(".table_action_buttons .btn").prop('disabled', true);

    // Enable/Disable table action buttons
    $('input[name="action_to[]"], .check-all').live('click', function () {

      if( $('input[name="action_to[]"]:checked, .check-all:checked').length >= 1 ){
        $(".table_action_buttons .btn").prop('disabled', false);
      } else {
        $(".table_action_buttons .btn").prop('disabled', true);
      }
    });

    $('a.mwi_request').live('click', function(e){
      e.preventDefault();

      var href    = $(this).attr('href'),
        module    = $(this).data('module');
        verb      = $(this).data('verb');

      post = {};
      post.csrf_token = CSRF_TOKEN;
      post._method = verb;

      if(typeof verb == undefined || verb == "GET")
      {
        window.location.replace(href);
      }
      else
      {
        $.post( href, post ,function(data) {
            //console.log(data);
        });
      }

      // redirect to reload page
      window.location.replace(BASE_URL + ADM_URI + module);
      
    });

    // Confirmation
    $('a.confirm').live('click', function(e){
      e.preventDefault();

      var href    = $(this).attr('href'),
        removemsg = $(this).data('title'),
        module    = $(this).data('module'),
        verb    = $(this).data('verb');
        response  = null;

      if (confirm(removemsg || mwi.lang.dialog_message))
      {
        $(this).trigger('click-confirmed');

        if ($.data(this, 'stop-click')){
          $.data(this, 'stop-click', false);
          return;
        }

        post = {};
        post.csrf_token = CSRF_TOKEN;
        post._method = verb;

        if(typeof verb == 'undefined')
        {
          verb = 'GET';
        }
        
        if(verb == 'GET')
        {
          window.location.replace(href);
          return;
        }
        else
        {
          $.post( href, post ,function(data) {

            var json = null;

            try
            {
              json = jQuery.parseJSON(data);
            }
            catch(e)
            {

            }
              
              if(json != null)
              {

                $.each(json, function(action, obj) { 
                  
                  if( action == "flash_message" )
                  {
                    $.each(obj.messages, function(action, msg) {
                      mwi.add_notification($('<div class="alert alert-'+obj.message_type+'">'+msg+'</div>'));
                    });
                  }

                  if( action == "html" )
                  {
                    $(obj.identifier).html(obj.partial);
                    //$('.module-description-info').popover({trigger: "hover", html: true});
                  }

                  if( action == "debug" )
                  {
                    //console.log(obj);
                  }

                  if( action == "hide" )
                  {
                    $(obj.identifier).hide();
                  }
                });
              }
              else
              {
                // redirect to reload page
                window.location.replace(BASE_URL + ADM_URI + module);
                //window.location.href = BASE_URL + ADM_URI + module;
              }
          });
        }
      }
    });

    //use a confirm dialog on "delete many" buttons
    $(':submit.confirm').live('click', function(e, confirmation){

      if (confirmation)
      {
        return true;
      }

      e.preventDefault();

      var removemsg = $(this).attr('title');

      if (confirm(removemsg || mwi.lang.dialog_message))
      {
        $(this).trigger('click-confirmed');

        if ($(this).data('stop-click')){
          $(this).data('stop-click', false);
          return;
        }

        $(this).trigger('click', true);
      }
    });

    // Table zerbra striping
    $("tbody tr:nth-child(even)").livequery(function () {
      $(this).addClass("alt");
    });


    $('#main, .tabs').livequery(function () {
      $(this).tabs();
      $(this).tabs('paging', { cycle: true, follow: false } );
    });

    $('#tabs').livequery(function () {
      $(this).tabs({
        // This allows for the Back button to work.
        select: function(event, ui) {
          parent.location.hash = ui.tab.hash;
        },
        load: function(event, ui) {
          confirm_links();
          confirm_buttons();
        }
      });
    });

    
    // Fancybox modal window
    $('a[rel=modal], a.modal').livequery(function() {
      $(this).colorbox({
        width: "60%",
        maxHeight: "90%",
        current: current_module + " {current} / {total}",
        onComplete: function(){ mwi.chosen() }
      });
    });

    $('a[rel="modal-large"], a.modal-large').livequery(function() {
      $(this).colorbox({
        width: "90%",
        height: "95%",
        iframe: true,
        scrolling: true,
        current: current_module + " {current} / {total}",
        onComplete: function(){ mwi.chosen() }
      });
    });
  };

  mwi.clear_notifications = function()
  {
    $('.alert .close').click();

    return mwi;
  };

  mwi.add_notification = function(notification, options, callback)
  {
    var defaults = {
      clear : true,
      ref   : '#flash-message',
      method: 'append'
    }, opt;

    // extend options
    opt = $.isPlainObject(options) ? $.extend(defaults, options) : defaults;

    // clear old notifications
    opt.clear && mwi.clear_notifications();

    // display current notifications
    $(opt.ref)[opt.method](notification);

    // call callback
    $(window).one('notification-complete', function(){
      callback && callback();
    });

    return mwi;
  };

  // Used by Pages and Navigation and is available for third-party add-ons.
  // Module must load jquery/jquery.ui.nestedSortable.js and jquery/jquery.cooki.js
  mwi.sort_tree = function($item_list, $url, $cookie, data_callback, post_sort_callback, sortable_opts)
  {
    // set options or create a empty object to merge with defaults
    sortable_opts = sortable_opts || {};
    
    // collapse all ordered lists but the top level
    $item_list.find('ul').children().hide();

    // this gets ran again after drop
    var refresh_tree = function() {

      // add the minus icon to all parent items that now have visible children
      $item_list.find('li:has(li:visible)').removeClass().addClass('minus');

      // add the plus icon to all parent items with hidden children
      $item_list.find('li:has(li:hidden)').removeClass().addClass('plus');
      
      // Remove any empty ul elements
      $('.plus, .minus').find('ul').not(':has(li)').remove();
      
      // remove the class if the child was removed
      $item_list.find("li:not(:has(ul li))").removeClass();

      // call the post sort callback
      post_sort_callback && post_sort_callback();
    }
    refresh_tree();

    // set the icons properly on parents restored from cookie
    $($.cookie($cookie)).has('ul').toggleClass('minus plus');

    // show the parents that were open on last visit
    $($.cookie($cookie)).children('ul').children().show();

    // show/hide the children when clicking on an <li>
    $item_list.find('li').live('click', function()
    {
      $(this).children('ul').children().slideToggle('fast');

      $(this).has('ul').toggleClass('minus plus');

      var items = [];

      // get all of the open parents
      $item_list.find('li.minus:visible').each(function(){ items.push('#' + this.id) });

      // save open parents in the cookie
      $.cookie($cookie, items.join(', '), { expires: 1 });

       return false;
    });
    
    // Defaults for nestedSortable
    var default_opts = {
      delay: 100,
      disableNesting: 'no-nest',
      forcePlaceholderSize: true,
      handle: 'div',
      helper: 'clone',
      items: 'li',
      opacity: .4,
      placeholder: 'placeholder',
      tabSize: 25,
      listType: 'ul',
      tolerance: 'pointer',
      toleranceElement: '> div',
      update: function(event, ui) {

        post = {};
        // create the array using the toHierarchy method
        post.order = $item_list.nestedSortable('toHierarchy');

        // pass to third-party devs and let them return data to send along
        if (data_callback) {
          post.data = data_callback(event, ui);
        }

        // Refresh UI (no more timeout needed)
        refresh_tree();

        post.csrf_token = CSRF_TOKEN;
        post._method = "PUT";

        $.post(SITE_URL + $url, post );
      }
    };

    // init nestedSortable with options
    $item_list.nestedSortable($.extend({}, default_opts, sortable_opts));
  }

  mwi.chosen = function()
  {
    // Chosen
    $('select:not(.skip)').livequery(function(){
      $(this).addClass('chzn').trigger("liszt:updated");
      $(".chzn").chosen();

      // This is a workaround for Chosen's visibility bug. In short if a select
      // is inside a hidden element Chosen sets the width to 0. This iterates through
      // the 0 width selects and sets a fixed width.
      $('.chzn-container').each(function(i, ele){
        if ($(ele).width() == 0) {
          $(ele).css('width', '236px');
          $(ele).find('.chzn-drop').css('width', '234px');
          $(ele).find('.chzn-search input').css('width', '200px');
          $(ele).find('.search-field input').css('width', '225px');
        }
      });
    });
  }


  // Whatches for user pressing tab for auto completion
  // on inputs named title
  $('input[name="title"]').live('keydown', function(e) { 

    mwi.generate_slug('input[name="title"]', 'input[name="slug"]');
    
    var keyCode = e.keyCode || e.which; 

    if (keyCode == 9) {
      e.preventDefault(); 
      mwi.generate_slug('input[name="title"]', 'input[name="slug"]');
    } 
  });

  // $('input[name="title"]').focusout(function() {
  //   mwi.generate_slug('input[name="title"]', 'input[name="slug"]');
  // });

  $('input[name="name"]').live('keydown', function(e) {     
    
    mwi.generate_slug('input[name="name"]', 'input[name="slug"]');
    
    var keyCode = e.keyCode || e.which;
    
    if (keyCode == 9) {
      e.preventDefault(); 
      mwi.generate_slug('input[name="name"]', 'input[name="slug"]');
    } 
  });

  // $('input[name="name"]').focusout(function() {
  //   mwi.generate_slug('input[name="name"]', 'input[name="slug"]');
  // });

  // Create a clean slug from whatever garbage is in the title field
  mwi.generate_slug = function(input_form, output_form, space_character)
  {
    var slug, value;

    $(input_form).live('keyup', function(){
      value = $(input_form).val();

      if ( ! value.length ) return;
      
      space_character = space_character || '-';
      var rx = /[a-z]|[A-Z]|[0-9]|[áàâąбćčцдđďéèêëęěфгѓíîïийкłлмñńňóôóпúùûůřšśťтвýыžżźзäæœчöøüшщßåяюжαβγδεέζηήθιίϊκλμνξοόπρστυύϋφχψωώ]/,
        value = value.toLowerCase(),
        chars = mwi.foreign_characters,
        space_regex = new RegExp('[' + space_character + ']+','g'),
        space_regex_trim = new RegExp('^[' + space_character + ']+|[' + space_character + ']+$','g'),
        search, replace;
      

      // If already a slug then no need to process any further
        if (!rx.test(value)) {
            slug = value;
        } else {
            value = $.trim(value);

            for (var i = chars.length - 1; i >= 0; i--) {
              // Remove backslash from string
              search = chars[i].search.replace(new RegExp('/', 'g'), '');
              replace = chars[i].replace;

              // create regex from string and replace with normal string
              value = value.replace(new RegExp(search, 'g'), replace);
            };

            slug = value.replace(/[^-a-z0-9~\s\.:;+=_]/g, '')
                  .replace(/[\s\.:;=+]+/g, space_character)
                  .replace(space_regex, space_character)
                  .replace(space_regex_trim, '');
        }

      $(output_form).val(slug);
    });
  }

  $(document).ajaxError(function(e, jqxhr, settings, exception) {
    mwi.add_notification($('<div class="alert alert-error">'+exception+'</div>'));
  });

  $(document).ajaxComplete(function() {
    // reload popup box for modules index page
    $('.module-description-info').popover({trigger: "hover", html: true});
  
  });

  $(document).ready(function() {
    mwi.init();
    mwi.chosen();
  });

  //close colorbox only when cancel button is clicked
  $('#cboxLoadedContent a.cancel').live('click', function(e) {
    e.preventDefault();
    $.colorbox.close();
  });


  // Title toggle
  $('a.toggle').click(function() {
     $(this).parent().next('.item').slideToggle(500);
  });

  // Draggable / Droppable
  $("#sortable").sortable({
    placeholder : 'dropzone',
      handle : '.draggable',
      update : function () {
        var order = $('#sortable').sortable('serialize');
      }
  });

  // Pretty Photo
  $('#main a:has(img)').addClass('prettyPhoto');
  $("a[class^='prettyPhoto']").prettyPhoto();

  // Tipsy
  $('.tooltip').tipsy({
    gravity: $.fn.tipsy.autoNS,
    fade: true,
    html: true
  });

  $('.tooltip-s').tipsy({
    gravity: 's',
    fade: true,
    html: true
  });

  $('.tooltip-e').tipsy({
    gravity: 'e',
    fade: true,
    html: true
  });

  $('.tooltip-w').tipsy({
    gravity: 'w',
    fade: true,
    html: true
  });

  //functions for codemirror
  $('.html_editor').each(function() {
    CodeMirror.fromTextArea(this, {
      stylesheet: "../css/codemirror/codemorror-style.css",
        mode: 'htmlmixed',
        tabMode: 'indent',
      height : '1900px',
      width : '500px',
    });
  });

  // var editor = CodeMirror.fromTextArea('code', {
 //        height: "350px",
 //        parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js",
 //                     "../contrib/php/js/tokenizephp.js", "../contrib/php/js/parsephp.js",
 //                     "../contrib/php/js/parsephphtmlmixed.js"],
 //        stylesheet: ["../../css/xmlcolors.css", "../../css/jscolors.css", "../../css/csscolors.css", "css/phpcolors.css"],
 //        path: "../../js/",
 //        continuousScanning: 500
 //      });

  $('.css_editor').each(function() {
    CodeMirror.fromTextArea(this, {
        mode: 'css',
        tabMode: 'indent',
      height : '500px',
      width : '500px',
    });
  });

  $('.js_editor').each(function() {
    CodeMirror.fromTextArea(this, {
        mode: 'javascript',
        tabMode: 'indent',
      height : '500px',
      width : '500px',
    });
  });

  $('.module-description-info').popover({trigger: "hover", html: true});
});
