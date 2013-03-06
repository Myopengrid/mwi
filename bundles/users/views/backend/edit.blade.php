<div style="margin-top:25px;" class="row">
    <div class="span12">
    {{Form::open( ADM_URI .'/users/'.$edit_user->id, 'PUT', array('class' => 'crud form-horizontal', 'id' => 'edit-user'));}}
        <div style="display:none">
            {{ Form::hidden('user_id', $edit_user->id ) }}
            {{ Form::token() }}
        </div>

        <div class="control-group {{ $errors->has('uuid') ? 'error' : '' }}">
            <label for="uuid" class="control-label">{{ Lang::line('users::lang.UUID')->get(ADM_LANG) }}</label>
            <div class="controls">
                {{ Form::text('uuidx', $edit_user->uuid, array('disabled' => 'disabled')) }}
                {{ Form::hidden('uuid', $edit_user->uuid ) }}
                <span class="help-inline">{{ $errors->has('uuid') ? $errors->first('uuid', '<small style="color:#dd3c10;">:message</small>') : '' }}</span>
            </div>
        </div>

        <div class="control-group {{ $errors->has('username') ? 'error' : '' }}">
            <label for="username" class="control-label">{{ Lang::line('users::lang.Username')->get(ADM_LANG) }}</label>
            <div class="controls">
                {{ Form::text('username', $edit_user->username) }}
                <span class="required-icon"></span>
                <span class="help-inline">{{ $errors->has('username') ? $errors->first('username', '<small style="color:#dd3c10;">:message</small>') : '' }}</span>
            </div>
        </div>

        <div class="control-group {{ $errors->has('avatar_first_name') ? 'error' : '' }}">
            <label for="avatar_first_name" class="control-label">{{ Lang::line('users::lang.Avatar First Name')->get(ADM_LANG) }}</label>
            <div class="controls">
                {{ Form::text('bavatar_first_name', $edit_user->avatar_first_name, array('disabled' => 'disabled')) }}
                {{ Form::hidden('avatar_first_name', $edit_user->avatar_first_name) }}
                <span class="help-inline">{{ $errors->has('avatar_first_name') ? $errors->first('avatar_first_name', '<small style="color:#dd3c10;">:message</small>') : '' }}</span>
            </div>
        </div>

        <div class="control-group {{ $errors->has('avatar_last_name') ? 'error' : '' }}">
            <label for="avatar_last_name" class="control-label">{{ Lang::line('users::lang.Avatar Last Name')->get(ADM_LANG) }}</label>
            <div class="controls">
                {{ Form::text('bavatar_last_name', $edit_user->avatar_last_name, array('disabled' => 'disabled')) }}
                {{ Form::hidden('avatar_last_name', $edit_user->avatar_last_name) }}
                <span class="help-inline">{{ $errors->has('avatar_last_name') ? $errors->first('avatar_last_name', '<small style="color:#dd3c10;">:message</small>') : '' }}</span>
            </div>
        </div>

        <div class="control-group {{ $errors->has('email') ? 'error' : '' }}">
            <label for="email" class="control-label">{{ Lang::line('users::lang.Email')->get(ADM_LANG) }}</label>
            <div class="controls">
                {{ Form::text('email', $edit_user->email) }}
                <span class="required-icon"></span>
                <span class="help-inline">{{ $errors->has('email') ? $errors->first('email', '<small style="color:#dd3c10;">:message</small>') : '' }}</span>
            </div>
        </div>

        @if(\Bundle::exists('groups'))
        @if($edit_user->is_core == 0)
        <div class="control-group {{ $errors->has('group_id') ? 'error' : '' }}">
            <label for="group_id" class="control-label">{{ Lang::line('users::lang.Group')->get(ADM_LANG) }}</label>
            <div class="controls">
                @if(isset($groups_dropdown) and ! empty($groups_dropdown))
                <select id="group_id" name="group_id">
                    <option value="0">{{ __('users::lang.-- Select --')->get(ADM_LANG) }}</option>
                    @foreach($groups_dropdown as $drop_item)
                    @if($edit_user->group_id == $drop_item->id)
                    <option selected="selected" value="{{ $drop_item->id }}">{{ $drop_item->name }}</option>
                    @else
                    <option value="{{ $drop_item->id }}">{{ $drop_item->name }}</option>
                    @endif
                    @endforeach
                </select>
                @else
                <select id="group_id" name="group_id">
                    <option value="0">{{ Lang::line('users::lang.-- Select --')->get(ADM_LANG) }}</option>
                    <option value="1">{{ Lang::line('users::lang.Administrators')->get(ADM_LANG) }}</option>
                    <option selected="selected" value="2">{{ Lang::line('users::lang.Users')->get(ADM_LANG) }}</option>
                </select>
                @endif
                <span class="help-inline">{{ $errors->has('group_id') ? $errors->first('group_id', '<small style="color:#dd3c10;">:message</small>') : '' }}</span>
            </div>
        </div>

        @else
        {{ Form::hidden('group_id', $edit_user->group_id)}}
        @endif

        @else
        {{ Form::hidden('group_id', $edit_user->group_id)}}
        @endif
                    
        <div class="control-group {{ $errors->has('status') ? 'error' : '' }}">
            <label for="status" class="control-label">{{ Lang::line('users::lang.Status')->get(ADM_LANG) }}</label>
            <div class="controls">
                <select name="status" id="status">
                    <?php $status_array = array('active', 'inactive', 'banned'); ?>
                    @foreach($status_array as $status)
                    @if($status == $edit_user->status)
                    <option selected="selected" value="{{ $status }}">{{ __('users::lang.'.$status)->get(ADM_LANG) }}</option>
                    @else
                    <option value="{{ $status }}">{{ __('users::lang.'.$status)->get(ADM_LANG) }}</option>
                    @endif
                    @endforeach
                </select>
                <span class="help-inline">{{ $errors->has('status') ? $errors->first('status', '<small style="color:#dd3c10;">:message</small>') : '' }}</span>
            </div>
        </div>

        <div class="control-group {{ $errors->has('password') ? 'error' : '' }}">
            <label for="password" class="control-label">{{ Lang::line('users::lang.Password')->get(ADM_LANG) }}</label>
            <div class="controls">
                {{ Form::password('password') }}
                <span class="help-inline">{{ $errors->has('password') ? $errors->first('password', '<small style="color:#dd3c10;">:message</small>') : '' }}</span>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" name="btnAction" value="save" class="btn btn-primary">
                <span>{{ Lang::line('users::lang.Save')->get(ADM_LANG) }}</span>
            </button>

            <a href="{{ URL::base().'/'.ADM_URI }}/users" class="btn">{{ Lang::line('users::lang.Cancel')->get(ADM_LANG) }}</a> 
        </div>

    {{ Form::close() }}
    </div>
</div>         