<section>
    <h3>{{__('install::lang.Step 2 - Configure Database')->get(Session::get('adm_lang'))}}</h3>

    <p>{{__('install::lang.Mwi requires a database to store all of your content and settings, so the first thing we need to do is check if the database connection  and database version are ok; If you do not understand what you are being asked to enter please ask your web hosting provider or server administrator for the details')->get(Session::get('adm_lang'))}}</p>
    <div class="span2"></div>
    <div class="span8">
        <form class="form-horizontal" action="{{URL::base()}}/index.php/install/step_2" id="install_frm" method="post" accept-charset="utf-8">

            <?php $db_options = array(
                        'mysql'   => 'Mysql (Recomended)',
                        'pgsql'  => 'PostgreSQL',
                        'sqlite' => 'SQLite',
                    );

                    if(PHP_OS != 'Linux')
                    {
                        $db_options['sqlsrv'] = 'Microsoft SQL Server (Windows only)';
                    }
            ?>
        
            <div class="control-group {{ $errors->has('driver') ? 'error' : ''; }}">
                <label for="driver" class="control-label">{{__('install::lang.Type')->get(Session::get('adm_lang'))}}</label>
                <div class="controls">
                    {{ Form::select('driver', $db_options, Input::old('driver', 'mysql')) }}
                    <span class="help-inline">{{ $errors->first('driver') }}</span>
                </div>
            </div>

            <div class="control-group host {{ $errors->has('host') ? 'error' : ''; }}">
                <label class="control-label" for="host">{{__('install::lang.Host')->get(Session::get('adm_lang'))}}</label>
                <div class="controls">
                    <input class="db_field" type="text" name="host" value="{{Input::old('host','127.0.0.1')}}" id="host"  />
                    <span class="help-inline">{{ $errors->first('host') }}</span>
                </div>
            </div>

            <div class="control-group database {{ $errors->has('database') ? 'error' : ''; }}">
                <label class="control-label" for="database">{{__('install::lang.Database Name')->get(Session::get('adm_lang'))}}</label>
                <div class="controls">
                    <input class="db_field" id="database" class="text" name="database" value="{{Input::old('database','')}}" type="text">
                    <span class="help-inline">{{ $errors->first('database') }}</span>
                </div>
            </div>

            <div class="control-group username {{ $errors->has('username') ? 'error' : ''; }}">
                <label class="control-label" for="username">{{__('install::lang.Username')->get(Session::get('adm_lang'))}}</label>
                <div class="controls">
                    <input class="db_field" type="text" name="username" value="{{Input::old('username','')}}" id="username"  />
                    <span class="help-inline">{{ $errors->first('username') }}</span>
                </div>
            </div>

            <div class="control-group password {{ $errors->has('password') ? 'error' : ''; }}">
                <label class="control-label" for="password">{{__('install::lang.Password')->get(Session::get('adm_lang'))}}</label>
                <div class="controls">
                    <input class="db_field" type="password" name="password" value="{{Input::old('password','')}}" id="password"  />
                     <span class="help-inline">{{ $errors->first('password') }}</span>
                </div>
            </div>

            <div class="control-group port {{ $errors->has('port') ? 'error' : ''; }}">
                <label class="control-label" for="port">{{__('install::lang.Port')->get(Session::get('adm_lang'))}}</label>
                <div class="controls">
                    <input class="db_field" type="text" name="port" value="{{Input::old('port','3306')}}" id="port"  />
                    <span class="help-inline">{{ $errors->first('port') }}</span>
                </div>
            </div>

            <div class="control-group prefix {{ $errors->has('prefix') ? 'error' : ''; }}">
                <label class="control-label" for="prefix">{{__('install::lang.Table Prefix')->get(Session::get('adm_lang'))}}</label>
                <div class="controls">
                    <input id="prefix" class="db_field" name="prefix" value="{{Input::old('prefix','mwi_')}}" type="text">
                    <span class="help-inline">{{ $errors->first('prefix') }}</span>
                </div>
            </div>


            <div id="db_flash_message" class="alert">
            </div>
            
            <br/>
            <input type="hidden" value="step_2" name="installation_step">
            <input type="submit" class="btn btn-primary step_2" value="{{__('install::lang.Step 3')->get(Session::get('adm_lang'))}}" id="next_step">
        </form>
    </div>
    <div class="span2"></div>
</section>