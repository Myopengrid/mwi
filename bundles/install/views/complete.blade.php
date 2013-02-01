<section>

    <h3><strong>{{__('install::lang.Congratulations')->get(Session::get('adm_lang'))}}</strong>, {{ $avatar_first_name . ' ' . $avatar_last_name }}</h3>

    <p>{{__('install::lang.Mwi is now installed and ready to go! Please log into the admin panel with the following details')->get(Session::get('adm_lang'))}}.</p>

    <div class="block-message">
        <p>
            <strong>{{__('install::lang.Avatar First Name')->get(Session::get('adm_lang'))}}: {{$avatar_first_name}}</strong>
        </p>
        <p>
            <strong>{{__('install::lang.Avatar Last Name')->get(Session::get('adm_lang'))}}: {{$avatar_last_name}}</strong>
        </p>
        <p>
            <strong>{{__('install::lang.Email')->get(Session::get('adm_lang'))}}: {{$email}}</strong>
        </p>

        <p class="password-reveal">
            <strong>{{__('install::lang.Password')->get(Session::get('adm_lang'))}}: <span class="password">{{$password}}</span></strong>
        </p>
        <p>
            <a class="btn btn-small show-pass" href="#">{{__('install::lang.Show Password?')->get(Session::get('adm_lang'))}}</a>
        </p>

        <p>{{__('install::lang.Finally')->get(Session::get('adm_lang'))}}, <strong class="alert-error">{{__('install::lang.disable and remove the installer module from your server, under modules section in the administration area')->get(Session::get('adm_lang'))}}.</strong></p>

        <hr/>
        <div class="btn-group">
            <a href="{{ URL::base() }}" class="btn btn-primary go_to_site">{{__('install::lang.Go to Website')->get(Session::get('adm_lang'))}}</a>

            <a href="{{ URL::base() }}/index.php/admin" class="btn btn-success go_to_site">{{__('install::lang.Go to Control Panel')->get(Session::get('adm_lang'))}}</a>
        </div>
    </div>
</section>
<script>
$(function()
{
    $('.password-reveal').hide();
    $('.show-pass').click(function(e){
        e.preventDefault();
        $(this).fadeOut().parent().prev('.password-reveal').delay(400).fadeIn();
    });
});
</script>