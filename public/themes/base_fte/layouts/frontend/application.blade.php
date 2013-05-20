<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{{ $meta_title.' - '.$site_name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $meta_description }}">
    <meta name="keywords" content="{{ $meta_keywords }}">

    <!-- Le styles -->
    <link href="{{ URL::base() }}/themes/base_fte/assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
    <link href="{{ URL::base() }}/themes/base_fte/assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="{{ URL::base() }}/themes/base_fte/assets/css/docs.css" rel="stylesheet">
    {{ Asset::styles() }}
    {{ Asset::container('page')->styles() }}

    <script src="{{ URL::base() }}/themes/base_fte/assets/js/jquery.js"></script>
    {{ Asset::scripts() }}
    {{ Asset::container('page')->scripts() }}

    <script type="text/javascript">
      var CSRF_TOKEN                  = "{{Session::token()}}";
      var BASE_URL                    = "{{URL::base()}}/";
      var SITE_URL                    = "{{URL::base()}}/";
    </script>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="shortcut icon" href="{{ URL::base() }}/themes/base_fte/assets/img/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ URL::base() }}/themes/base_fte/assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ URL::base() }}/themes/base_fte/assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ URL::base() }}/themes/base_fte/assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="{{ URL::base() }}/themes/base_fte/assets/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body>

    {{ themes\render_partial('header_nav') }}

    <div class="container-fluid">
      <div class="row-fluid">
        
        <div class="span3">

        {{ themes\render_partial('sidebar_nav') }}

        </div><!--/span-->
        
        <div style="min-height:200px; margin-top:15px;" class="span9">

          <div id="flash-message" class="flash-message">
            @if (Session::has('message'))
            <div class="alert alert-{{ Session::get('message_type') }} fade in">
              <button data-dismiss="alert" class="close" type="button">×</button>
              {{ Session::get('message') }}
            </div>
            @endif

            @if( !empty($errors->messages) )
            <div class="alert alert-error fade in">
              <button data-dismiss="alert" class="close" type="button">×</button>
              {{ $errors->first() }}
            </div>
            @endif
          </div>
          
          {{ $theme_content }}

        </div><!--/span-->

      </div><!--/row-->

      <hr>

      <div>

        {{ themes\render_partial('footer') }}
        
      </div>

    </div><!--/.fluid-container-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="{{ URL::base() }}/themes/base_fte/assets/js/bootstrap.min.js"></script>
    {{ Asset::container('footer')->scripts() }}
  </body>
</html>