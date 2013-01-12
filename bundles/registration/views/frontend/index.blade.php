<style type="text/css">

body {
    padding-top: 40px;
    padding-bottom: 40px;
    background-color: #f5f5f5;
}

.form-signin {
    max-width: 600px;
    padding: 19px 29px 29px;
    margin: 0 auto 20px;
    background-color: #fff;
    border: 1px solid #e5e5e5;
    -webkit-border-radius: 5px;
       -moz-border-radius: 5px;
            border-radius: 5px;
    -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
       -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
            box-shadow: 0 1px 2px rgba(0,0,0,.05);
}

.form-signin .form-signin-heading,
.form-signin .checkbox {
    margin-bottom: 10px;
}

.form-signin input[type="text"],
.form-signin input[type="password"] {
    font-size: 16px;
    height: auto;
    margin-bottom: 15px;
    padding: 7px 9px;
}

#auth-login-container {
    margin-top: 15px;
}
</style>
<div class="row">
    <div class="span11">
        <div id="auth-login-container">
            {{ \Form::open(URL::base().'/signup', 'POST', array('class' => 'form-signin' )) }}

                {{ \Form::token() }}
                
                <h2 class="form-signin-heading">Sign Up</h2>
                
                <div class="control-group {{ $errors->has('email') ? 'error' : '' }}">
                    <div class="controls">
                        {{ \Form::text('email', Input::old('email'), array('class' => 'input-block-level', 'placeholder' => 'Email address' )) }}

                        <span class="help-inline">{{ $errors->has('email') ? $errors->first('email', '<div class="auth-login-error">:message</div>') : '' }}</span>
                    </div>
                </div>

                <div class="control-group {{ $errors->has('username') ? 'error' : '' }}">
                    <div class="controls">
                        {{ \Form::text('username', Input::old('username'), array('class' => 'input-block-level', 'placeholder' => 'Username' )) }}

                        <span class="help-inline">{{ $errors->has('username') ? $errors->first('username', '<div class="auth-login-error">:message</div>') : '' }}</span>
                    </div>
                </div>


                <div class="control-group {{ $errors->has('avatar_first_name') ? 'error' : '' }}">
                    <div class="controls">
                        {{ \Form::text('avatar_first_name', Input::old('avatar_first_name'), array('class' => 'input-block-level', 'placeholder' => 'Desired Avatar First Name' )) }}

                        <span class="help-inline">{{ $errors->has('avatar_first_name') ? $errors->first('avatar_first_name', '<div class="auth-login-error">:message</div>') : '' }}</span>
                    </div>
                </div>

                <div class="control-group {{ $errors->has('avatar_last_name') ? 'error' : '' }}">
                    <div class="controls">
                        {{ \Form::text('avatar_last_name', Input::old('avatar_last_name'), array('class' => 'input-block-level', 'placeholder' => 'Desired Avatar Last Name' )) }}

                        <span class="help-inline">{{ $errors->has('avatar_last_name') ? $errors->first('avatar_last_name', '<div class="auth-login-error">:message</div>') : '' }}</span>
                    </div>
                </div>


                <div class="control-group {{ $errors->has('password') ? 'error' : '' }}">
                    <div class="controls">
                        {{ \Form::password('password', array('class' => 'input-block-level', 'placeholder' => 'Password')) }}

                        <span class="help-inline">{{ $errors->has('password') ? $errors->first('password', '<div class="auth-login-error">:message</div>') : '' }}</span>
                    </div>
                </div>

                <div class="control-group {{ $errors->has('password_confirmation') ? 'error' : '' }}">
                    <div class="controls">
                        {{ \Form::password('password_confirmation', array('class' => 'input-block-level', 'placeholder' => 'Password Confirmation')) }}

                        <span class="help-inline">{{ $errors->has('password_confirmation') ? $errors->first('password_confirmation', '<div class="auth-login-error">:message</div>') : '' }}</span>
                    </div>
                </div>

                {{ \Form::button('Register', array('class' => 'btn btn-large btn-success')) }}

            {{ \Form::close() }}

        </div>
    </div>
</div>