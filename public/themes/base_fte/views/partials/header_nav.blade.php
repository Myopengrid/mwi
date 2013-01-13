<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container-fluid">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="brand" href="{{ URL::base() }}">{{ Config::get('settings::core.site_name') }}</a>
      <div class="nav-collapse collapse">
        @if(Auth::check())
        <p class="navbar-text pull-right">
          Logged in as <a href="#" class="navbar-link">{{Auth::user()->username}}</a> 
          <a class="navbar-link" href="{{ URL::base() }}/logout">Logout</a>
        </p>
        @else
        @if(Bundle::exists('auth'))
        <p class="navbar-text pull-right">
          <a href="{{ URL::base()}}/login" class="navbar-link">Login</a>
        </p>
        @endif
        @endif
        <ul class="nav">
          {{ \IoC::resolve('Menu')->get_nav('header') }}
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>