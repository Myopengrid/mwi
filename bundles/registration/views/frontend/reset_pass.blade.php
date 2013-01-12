<style type="text/css">

body {
    padding-top: 40px;
    padding-bottom: 40px;
    background-color: #f5f5f5;
}

.form-signin {
    max-width: 300px;
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
    <div class="span8">
        <div id="auth-login-container">
            {{ \Form::open(URL::base().'/registration/reset_pass', 'POST', array('class' => 'form-signin' )) }}

                {{ \Form::token() }}
                {{ \Form::hidden('user_id', $user_id) }}
                {{ \Form::hidden('code', $code) }}
                
                <h2 class="form-signin-heading">New Password</h2>
                
                <div class="control-group {{ $errors->has('password') ? 'error' : '' }}">
                    <div class="controls">
                        {{ \Form::password('password', array('class' => 'input-block-level', 'placeholder' => 'Enter new password' )) }}

                        <span class="help-inline">{{ $errors->has('password') ? $errors->first('password', '<div class="auth-login-error">:message</div>') : '' }}</span>
                    </div>
                </div>

                <div class="control-group {{ $errors->has('password_confirmation') ? 'error' : '' }}">
                    <div class="controls">
                        {{ \Form::password('password_confirmation', array('class' => 'input-block-level', 'placeholder' => 'Confirm new password' )) }}

                        <span class="help-inline">{{ $errors->has('password_confirmation') ? $errors->first('password_confirmation', '<div class="auth-login-error">:message</div>') : '' }}</span>
                    </div>
                </div>
                {{ \Form::button('Save', array('class' => 'btn btn-large btn-primary')) }}

            {{ \Form::close() }}
        </div>
    </div>
</div>