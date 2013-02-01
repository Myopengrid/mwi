<section id="installer-start-page">
  <div class="page-header">
    <h1> {{ __('install::lang.Getting Started')->get(Session::get('adm_lang')) }}</h1>
  </div>
  <p class="lead">{{ __('install::lang.Installing Mwi CMS is very easy&#46; just follow the steps and messages on the screen&#46; In case you have any problems installing the application dont worry, the installer will explain what you need to do')->get(Session::get('adm_lang'))}}.</p>
     <a class="btn btn-primary btn-large" href="{{ URL::base()}}/index.php/install/step_1">{{__('install::lang.Start')->get(Session::get('adm_lang'))}}</a>
</section>