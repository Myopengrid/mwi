<div class="row">
    <div class="span12">
        <ul style="margin:50px;" class="thumbnails background">

            @if(Bundle::exists('pages'))
            <li class="thumb-image span2">
            <a href="{{ URL::base().'/'.ADM_URI.'/'}}pages" class="thumbnail">
                <i class="icon-book"></i> Pages 
            </a>
            </li>
            @endif

            @if(Bundle::exists('users'))
            <li class="thumb-image span2">
            <a href="{{ URL::base().'/'.ADM_URI.'/'}}users" class="thumbnail">
                <i class="icon-user"></i> Users
            </a>
            </li>
            @endif

            @if(Bundle::exists('email'))
            <li class="thumb-image span2">
            <a href="{{ URL::base().'/'.ADM_URI.'/'}}email/new" class="thumbnail">
                <i class="icon-envelope"></i> Email
            </a>
            </li>
            @endif

            @if(Bundle::exists('opensim'))
            <li class="thumb-image span2">
            <a href="{{ URL::base().'/'.ADM_URI.'/'}}opensim" class="thumbnail">
                <i class="icon-road"></i> Opensim
            </a>
            </li>
            @endif

            @if(Bundle::exists('splashscreen'))
            <li class="thumb-image span2">
            <a href="{{ URL::base().'/'.ADM_URI.'/'}}splashscreen" class="thumbnail">
                <i class="icon-tasks"></i> Splash Screen
            </a>
            </li>
            @endif

          </ul>
    </div>
</div>