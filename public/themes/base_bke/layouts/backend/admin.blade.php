<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{{ $page_title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="{{ URL::base() }}/themes/base_bke/assets/css/bootstrap.css" rel="stylesheet">
    <link href="{{ URL::base() }}/themes/base_bke/assets/css/docs.css" rel="stylesheet">
    <link href="{{ URL::base() }}/themes/base_bke/assets/css/chosen.css" rel="stylesheet">
    <link href="{{ URL::base() }}/themes/base_bke/assets/css/application.css" rel="stylesheet">
    <link href="{{ URL::base() }}/themes/base_bke/assets/css/jquery/colorbox.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
    <link href="{{ URL::base() }}/themes/base_bke/assets/css/bootstrap-responsive.css" rel="stylesheet">
    {{ "\n".Asset::styles() }}
    {{ "\n".Asset::container('before_header')->styles() }}
    {{ "\n".Asset::container('header')->styles() }}
    {{ "\n".Asset::container('after_header')->styles() }}
    {{ "\n".Asset::container('page')->styles() }}

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->


    <script type="text/javascript">
      var ADM_URI                     = "{{ADM_URI}}/";
      var CSRF_TOKEN                  = "{{Session::token()}}";
      var BASE_URL                    = "{{URL::base()}}/";
      var SITE_URL                    = "{{URL::base()}}/";
    </script>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <?php themes\add_asset('jquery.js') ?>
    <?php themes\add_asset('jquery-ui.min.js', 'jquery') ?>
    <?php themes\add_asset('bootstrap.min.js') ?>
    {{ "\n".Asset::scripts() }}
    {{ "\n".Asset::container('page')->scripts() }}

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="/themes/base_bke/assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/themes/base_bke/assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/themes/base_bke/assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/themes/base_bke/assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="/themes/base_bke/assets/ico/apple-touch-icon-57-precomposed.png">
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

      <div id="overview" class="subhead">
        @if(isset($bar['url']) and !empty($bar['url']))
        <h2 style="display:inline"><a href="{{ $bar['url'] }}">{{ Str::title($bar['title']) }}</a></h2>
        @endif
        @if(isset($bar['description']) and !empty($bar['description']))
        <small>   {{$bar['description']}}</small>
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
      </div>
      
      <div id="flash-message" class="flash-message">
        @if (Session::has('message'))
        <div class="alert alert-{{ Session::get('message_type') }} fade in">
          {{ Session::get('message') }}
        </div>  
        @endif

        @if (isset($message) and !empty($message))
        <div class="alert alert-{{ $message_type }} fade in">
          {{ $message }}
        </div>  
        @endif
        
        @if( !empty($errors->messages) )
        <div class="alert alert-error fade in">
          @foreach($errors->all('<p>:message</p>') as $error_message)
          {{ $error_message }}
          @endforeach
        </div>  
        @endif
      </div>

      {{ $theme_content }}

      <div style="padding:20px;">

        <p class="pull-left"><a style="margin-right:10px" href="#">Back to top</a></p>
        <p class="pull-left">Powered by <a href="http://myopengrid.com">Myopengrid MWI</a>   
          -- Rendered in 0.1223 sec. 
          Using <?php  echo round(memory_get_usage(true)/1048576,2). ' MB'; ?>.
        </p>
        
        <?php $available_languages = json_decode(Settings\Config::get('settings::core.available_languages')); ?>
             
                <form class="pull-right" action="{{ URL::base().'/'.ADM_URI.'/' }}settings/set_language" id="change_language" method="post">
                    {{ Form::token() }}
                    {{ Form::hidden('_method', 'PUT') }}
                    {{ Form::hidden('redirect_to', URI::current()) }}
                    @if(isset($available_languages))
                    <select class="chzn" name="lang" onchange="this.form.submit();">

                        @foreach($available_languages as $key => $value)
                            @if($key == ADM_LANG)
                            <option value="{{ $key }}" selected="selected">{{ $value }}</option>
                            @else
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>
                    @endif
                </form>
            
      </div>

    </div>
    <?php themes\add_asset('application.js', null , array(), 'footer') ?>
    <?php themes\add_asset('scripts.js', null , array(), 'footer') ?>
    <?php themes\add_asset('plugins.js', null , array(), 'footer') ?>
    {{ "\n".Asset::container('before_footer')->scripts() }}
    {{ "\n".Asset::container('footer')->scripts() }}
    {{ "\n".Asset::container('after_footer')->scripts() }}
    {{ "\n".Asset::container('before_modules_footer')->scripts() }}
    {{ "\n".Asset::container('modules_footer')->scripts() }}
    {{ "\n".Asset::container('after_modules_footer')->scripts() }}

  </body>
</html>
