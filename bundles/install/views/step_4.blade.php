<div class="span1"></div>
    <div class="span10">

        <h3>{{ __('install::lang.Default User')->get(Session::get('adm_lang')) }}</h3>
        

        <form class="form-horizontal" action="{{URL::base()}}/index.php/install/step_4" id="install_frm" method="post" accept-charset="utf-8">

            <div class="control-group {{ $errors->has('uuid') ? 'error' : ''; }}">
                <label for="uuid" class="control-label">{{ __('install::lang.UUID')->get(Session::get('adm_lang')) }}</label>
                <div class="controls">
                    <input type="text" name="uuid" value="{{Input::old('uuid', $uuid)}}" id="uuid"  />
                    <span class="help-inline">{{ $errors->first('uuid') }}</span>
                </div>
            </div>

            <div class="control-group {{ $errors->has('user_name') ? 'error' : ''; }}">
                <label for="user_name" class="control-label">{{ __('install::lang.Username')->get(Session::get('adm_lang')) }}</label>
                <div class="controls">
                    <input type="text" name="user_name" value="{{Input::old('user_name', '')}}" id="user_name"  />
                    <span class="help-inline">{{ $errors->first('user_name') }}</span>
                </div>
            </div>

            <div class="control-group {{ $errors->has('avatar_first_name') ? 'error' : ''; }}">
                <label for="avatar_first_name" class="control-label">{{ __('install::lang.Avatar First Name')->get(Session::get('adm_lang')) }}</label>
                <div class="controls">
                    <input type="text" name="avatar_first_name" value="{{Input::old('avatar_first_name', '')}}" id="avatar_first_name"  />
                    <span class="help-inline">{{ $errors->first('avatar_first_name') }}</span>
                </div>
            </div>

            <div class="control-group {{ $errors->has('avatar_last_name') ? 'error' : ''; }}">
                <label for="avatar_last_name" class="control-label">{{ __('install::lang.Avatar Last Name')->get(Session::get('adm_lang')) }}</label>
                <div class="controls">
                    <input type="text" name="avatar_last_name" value="{{Input::old('avatar_last_name', '')}}" id="avatar_last_name"  />
                    <span class="help-inline">{{ $errors->first('avatar_last_name') }}</span>
                </div>
            </div>

            <div class="control-group {{ $errors->has('email') ? 'error' : ''; }}">
                <label for="email" class="control-label">{{ __('install::lang.Email')->get(Session::get('adm_lang')) }}</label>
                <div class="controls">
                    <input type="text" name="email" value="{{Input::old('email', '')}}" id="email"  />
                    <span class="help-inline">{{ $errors->first('email') }}</span>
                </div>
            </div>

            <div class="control-group {{ $errors->has('password') ? 'error' : ''; }}">
                <label for="password" class="control-label">{{ __('install::lang.Password')->get(Session::get('adm_lang')) }}</label>
                <div class="controls">
               <input type="password" name="password" value="" id="password"  />
                <span class="help-inline">{{ $errors->first('password') }}</span>
                </div>
            </div>

        <div id="confirm_pass"></div>
        <br />
        <p class="success">{{ __('install::lang.Uff! Holly GuacaMolly! By now we have all information necessary to start the installation; (Or we think we do ;)) Please hold on tight and click the install button bellow!')->get(Session::get('adm_lang')) }}</p>
        <input class="btn btn-success" id="next_step" value="{{ __('install::lang.Install')->get(Session::get('adm_lang')) }}" type="submit">
    </form>
</section>

</div>
<div class="span1"></div>