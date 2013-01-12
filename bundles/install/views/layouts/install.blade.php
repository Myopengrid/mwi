<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ $title }}</title>

    {{ Asset::styles() }}
    {{ Asset::container('header')->styles() }}

    <!-- Le styles -->
    <link href="{{ URL::base() }}/bundles/install/css/bootstrap.css" rel="stylesheet">
    <link href="{{ URL::base() }}/bundles/install/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="{{ URL::base() }}/bundles/install/css/docs.css" rel="stylesheet">
    <link href="{{ URL::base() }}/bundles/install/css/prettify.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="assets/ico/favicon.png">

    <script type="text/javascript">
        var base_url = '{{ URL::base() }}/install/',
        pass_match = ['Passwords Match.','Passwords Don\'t Match.'];
    </script>

    {{ Asset::scripts() }}
    {{ Asset::container('header')->scripts() }}

  </head>

  <body data-spy="scroll" data-target=".bs-docs-sidebar">

    <!-- Navbar
    ================================================== -->
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="./index.html" class="brand">Sagui CMS</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active">
                <a class="flag" data-lang="us" href="#" title="English">
                  <img src="{{URL::base()}}/bundles/install/images/flags/gb.gif" alt="English" />
                </a>
              </li>
              <li>
               <a class="flag" data-lang="br" href="#" title="Brazilian Portuguese">
                    <img src="{{URL::base()}}/bundles/install/images/flags/br.gif" alt="Brazilian Portuguese" />
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

  <div class="container">
    <div class="pagination pagination-centered">
              <ul>
                <li><a href="{{ URL::base() }}/install">{{__('install::lang.Start')->get(Session::get('adm_lang'))}}</a></li>
                <li class="{{ $nav_step >= 1 ? 'active' : '' }}"><a>{{__('install::lang.Step 1')->get(Session::get('adm_lang'))}}</a></li>
                <li class="{{ $nav_step >= 2 ? 'active' : '' }}"><a>{{__('install::lang.Step 2')->get(Session::get('adm_lang'))}}</a></li>
                <li class="{{ $nav_step >= 3 ? 'active' : '' }}"><a>{{__('install::lang.Step 3')->get(Session::get('adm_lang'))}}</a></li>
                <li class="{{ $nav_step >= 4 ? 'active' : '' }}"><a>{{__('install::lang.Step 4')->get(Session::get('adm_lang'))}}</a></li>
                <li class="{{ $nav_step >= 5 ? 'active' : '' }}"><a>{{__('install::lang.Final Step')->get(Session::get('adm_lang'))}}</a></li>
             </ul>
            </div>
    <div class="row">
      
      <div class="span12">
        {{ $content }}
      </div>
    </div>
    </div>
    </div>

      
    <!-- Footer
    ================================================== -->
    <footer class="footer">
      <div class="container">
        <p>{{__('install::lang.Designed and built with all the love in the world by')->get(Session::get('adm_lang')) }} <a href="http://myopengrid.com" target="_blank">Myopengrid</a></p>
        <p><a href="http://opensource.org/licenses/mit-license.php" target="_blank"> {{__('install::lang.Code licensed under MIT License')->get(Session::get('adm_lang')) }} </a></p>
      </div>
    </footer>
  </body>
</html>
