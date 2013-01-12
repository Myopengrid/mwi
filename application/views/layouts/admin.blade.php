<!DOCTYPE html>
<h1>ADMIN FALLBACK LAYOUT</h1>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{{ $page_title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="{{ URL::base() }}/base/backend/assets/css/bootstrap.css" rel="stylesheet">
    <link href="{{ URL::base() }}/base/backend/assets/css/docs.css" rel="stylesheet">
    <link href="{{ URL::base() }}/base/backend/assets/css/chosen.css" rel="stylesheet">
    <link href="{{ URL::base() }}/base/backend/assets/css/application.css" rel="stylesheet">
    <link href="{{ URL::base() }}/base/backend/assets/css/jquery/colorbox.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
    <link href="{{ URL::base() }}/base/backend/assets/css/bootstrap-responsive.css" rel="stylesheet">
    {{ Asset::styles() }}
    {{ Asset::container('page')->styles() }}

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->


    <script type="text/javascript">
      pyro = { 'lang' : {} };
      var ADM_URI                     = "{{ADM_URI}}/";
      var CSRF_TOKEN                  = "{{Session::token()}}";
      var BASE_URL                    = "{{URL::base()}}/";
      var SITE_URL                    = "{{URL::base()}}/";
    </script>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="{{ URL::base() }}/base/backend/assets/js/jquery.js"></script>
    <script src="{{ URL::base() }}/base/backend/assets/js/bootstrap-transition.js"></script>
    <script src="{{ URL::base() }}/base/backend/assets/js/bootstrap-alert.js"></script>
    <script src="{{ URL::base() }}/base/backend/assets/js/bootstrap-modal.js"></script>
    <script src="{{ URL::base() }}/base/backend/assets/js/bootstrap-dropdown.js"></script>
    <script src="{{ URL::base() }}/base/backend/assets/js/bootstrap-scrollspy.js"></script>
    <script src="{{ URL::base() }}/base/backend/assets/js/bootstrap-tab.js"></script>
    <script src="{{ URL::base() }}/base/backend/assets/js/bootstrap-tooltip.js"></script>
    <script src="{{ URL::base() }}/base/backend/assets/js/bootstrap-popover.js"></script>
    <script src="{{ URL::base() }}/base/backend/assets/js/bootstrap-button.js"></script>
    <script src="{{ URL::base() }}/base/backend/assets/js/bootstrap-collapse.js"></script>
    <script src="{{ URL::base() }}/base/backend/assets/js/bootstrap-carousel.js"></script>
    <script src="{{ URL::base() }}/base/backend/assets/js/bootstrap-typeahead.js"></script>
    <script src="{{ URL::base() }}/base/backend/assets/js/application.js"></script>
    <!--<script src="{{ URL::base() }}/base/assets/js/bootbox.js"></script> -->

    <?php themes\add_asset('jquery-ui.min.js', 'jquery') ?>
    
    <?php themes\add_asset('jquery.colorbox.js', 'jquery' , array(), 'page') ?>
    <?php themes\add_asset('scripts.js', null , array(), 'page') ?>
    <?php themes\add_asset('plugins.js', null , array(), 'page') ?>

    {{ Asset::scripts() }}
    {{ Asset::container('page')->scripts() }}

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="base/backend/assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="base/backend/assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="base/backend/assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="base/backend/assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="base/backend/assets/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="{{ Url::base() }}" class="brand">{{ Config::get('settings::core.site_name') }}</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
               {{ themes\render_admin_top_menu($installed_and_active_modules) }}
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="container">

      <header id="overview" class="subhead">
        @if(isset($bar['url']) and !empty($bar['url']))
        <h2 style="display:inline"><a href="{{ $bar['url'] }}">{{ Str::title($bar['title']) }}</a></h2>
        @endif
        @if(isset($bar['description']) and !empty($bar['description']))
        <small>&nbsp; &nbsp;{{$bar['description']}}</small>
        @endif
        <div class="subnav">
    @if(isset($section_bar) and isset($section_bar_active))
        <ul class="nav nav-pills">
            @foreach($section_bar as $title => $url)
            @if($section_bar_active == $title)
            <li class="active">
            @else
            <li>
            @endif
            <a href="{{$url}}">{{ ucfirst($title) }}</a>
            </li>
            @endforeach
            @if(isset($bar['buttons']) and !empty($bar['buttons']))
            @foreach($bar['buttons'] as $shortcut)
            <li><a class="{{$shortcut['options']['class']}}" href="{{$shortcut['url']}}">{{ ucfirst($shortcut['name']) }}</a>
            </li>
            @endforeach
            @endif
        </ul>
    @endif   
        </div>
      </header>
      

      @if (Session::has('message'))
      <div class="alert alert-{{ Session::get('message_type') }} fade in">
        {{ Session::get('message') }}
      </div>  
      @endif

      {{ $theme_content }}

      <footer style="padding:20px;">

        <p class="pull-left"><a href="#">Back to top</a></p>
        <p class="pull-left">&nbsp; Copyright &copy; 2009 - 2012 MWI &nbsp; -- &nbsp; &nbsp; Rendered in 0.1223 sec. Using <?php  echo round(memory_get_usage(true)/1048576,2). ' MB'; ?>.</p>
        
        <?php $available_languages = array(
                    'English'                       => 'en',
                    'Portugu&ecirc;s do Brasil'     => 'br',
                  );
            ?>
             <ul>
                <form class="pull-right" action="{{ URL::base().'/'.ADM_URI.'/' }}settings/ajax_set_language" id="change_language" method="post">
                    {{ Form::token() }}
                    {{ Form::hidden('redirect_to', URI::current()) }}
                    <select class="chzn" name="lang" onchange="this.form.submit();">
                        @foreach($available_languages as $language => $value)
                            @if($value == ADM_LANG)
                            <option value="{{ $value }}" selected="selected">{{ $language }}</option>
                            @else
                            <option value="{{ $value }}">{{ $language }}</option>
                            @endif
                        @endforeach
                    </select>
                </form>
            </ul>
      </footer>

    </div>
  </body>
</html>
