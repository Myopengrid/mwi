<div style="margin-top:25px;" class="row">
    <div class="span12">
    {{ Form::open(ADM_URI.'/users/', 'POST', array('class' => 'form-horizontal', 'id' => 'create-user')) }}
        
        <div style="display:none">
            {{ Form::token() }}
        </div>  

        <!-- Content tab -->
        <div class="draggable-field">

            <div class="control-group {{ $errors->has('uuid') ? 'error' : '' }}">
                <label for="uuid" class="control-label">{{ Lang::line('users::lang.UUID')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::text('uuid', Input::old('uuid', $uuid)) }}
                    <span class="required-icon"></span>
                    <span class="help-inline">{{ $errors->has('uuid') ? $errors->first('uuid', '<small style="color:#dd3c10;">:message</small>') : '' }}</span>
                </div>
            </div>

            <div class="control-group {{ $errors->has('username') ? 'error' : '' }}">
                <label for="username" class="control-label">{{ Lang::line('users::lang.Username')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::text('username', Input::old('username', '')) }}
                    <span class="required-icon"></span>
                    <span class="help-inline">{{ $errors->has('username') ? $errors->first('username', '<small style="color:#dd3c10;">:message</small>') : '' }}</span>
                </div>
            </div>

            <div class="control-group {{ $errors->has('avatar_first_name') ? 'error' : '' }}">
                <label for="avatar_first_name" class="control-label">{{ Lang::line('users::lang.Avatar First Name')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::text('avatar_first_name', Input::old('avatar_first_name', '')) }}
                    <span class="required-icon"></span>
                    <span class="help-inline">{{ $errors->has('avatar_first_name') ? $errors->first('avatar_first_name', '<small style="color:#dd3c10;">:message</small>') : '' }}</span>
                </div>
            </div>

            <div class="control-group {{ $errors->has('avatar_last_name') ? 'error' : '' }}">
                <label for="avatar_last_name" class="control-label">{{ Lang::line('users::lang.Avatar Last Name')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::text('avatar_last_name', Input::old('avatar_last_name', '')) }}
                    <span class="required-icon"></span>
                    <span class="help-inline">{{ $errors->has('avatar_last_name') ? $errors->first('avatar_last_name', '<small style="color:#dd3c10;">:message</small>') : '' }}</span>
                </div>
            </div>

            <div class="control-group {{ $errors->has('email') ? 'error' : '' }}">
                <label for="email" class="control-label">{{ Lang::line('users::lang.Email')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::text('email', Input::old('email', '')) }}
                    <span class="required-icon"></span>
                    <span class="help-inline">{{ $errors->has('email') ? $errors->first('email', '<small style="color:#dd3c10;">:message</small>') : '' }}</span>
                </div>
            </div>

            @if(Bundle::exists('groups'))
            <div class="control-group {{ $errors->has('group_id') ? 'error' : '' }}">
                <label for="group_id" class="control-label">{{ Lang::line('users::lang.Group')->get(ADM_LANG) }}</label>
                <div class="controls">
                    <select name="group_id" id="group_id">
                        <option value="0">{{ Lang::line('users::lang.-- Select --')->get(ADM_LANG) }}</option>
                        @foreach($groups_dropdown as $group)
                        @if($group->slug == 'users')
                        <option selected="selected" value="<?php echo $group->id ?>">{{ $group->name }}</option>
                        @else
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endif
                        @endforeach
                    </select>
                    <span class="help-inline">{{ $errors->has('group_id') ? $errors->first('group_id', '<small style="color:#dd3c10;">:message</small>') : '' }}</span>
                </div>
            </div>
            @endif


            <div class="control-group {{ $errors->has('status') ? 'error' : '' }}">
                <label for="status" class="control-label">{{ Lang::line('users::lang.Status')->get(ADM_LANG) }}</label>
                <div class="controls">
                    <select name="status" id="status">
                        <option value="active">{{ Lang::line('users::lang.Active')->get(ADM_LANG) }}</option>
                        <option value="inactive">{{ Lang::line('users::lang.Inactive')->get(ADM_LANG) }}</option>
                        <option value="banned">{{ Lang::line('users::lang.Banned')->get(ADM_LANG) }}</option>
                    </select>
                    <span class="help-inline">{{ $errors->has('status') ? $errors->first('status', '<small style="color:#dd3c10;">:message</small>') : '' }}</span>
                </div>
            </div>

            <div class="control-group {{ $errors->has('password') ? 'error' : '' }}">
                <label for="password" class="control-label">{{ Lang::line('users::lang.Password')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::password('password') }}
                    <span class="required-icon"></span>
                    <span class="help-inline">{{ $errors->has('password') ? $errors->first('password', '<small style="color:#dd3c10;">:message</small>') : '' }}</span>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" name="btnAction" value="save" class="btn btn-primary">
                <span>{{ Lang::line('users::lang.Save')->get(ADM_LANG) }}</span>
            </button>

            <a href="{{ URL::base() }}/admin/users" class="btn">{{ Lang::line('users::lang.Cancel')->get(ADM_LANG) }}</a> 
        </div>
    {{ Form::close() }}
    </div>
</div>         