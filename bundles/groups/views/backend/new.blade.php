<?php themes\add_asset('check_slug.js', 'mod: groups/js', array(), 'footer') ?>
<div style="margin-top:25px;" class="row">
    <div class="span12">
        {{Form::open( URL::base() .'/'.ADM_URI.'/groups/', 'POST', array('class' => 'form-horizontal'))}}
        <div style="display:none">
            {{Form::token()}}
        </div>
        <div class="form_inputs">

            <div class="control-group {{ $errors->has('name') ? 'error' : '' }}">
                <label for="name" class="control-label">{{ Lang::line('groups::lang.Name')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::text('name', Input::old('name', '')) }}
                    <span class="required-icon"></span>
                    <span class="help-inline">{{ $errors->has('name') ? $errors->first('name') : '' }}</span>
                </div>
            </div>

            <div class="control-group {{ $errors->has('slug') ? 'error' : '' }}">
                <label for="slug" class="control-label">{{ Lang::line('groups::lang.Short Name')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::text('slug', Input::old('slug', '')) }}
                    <span class="required-icon"></span>
                    <span class="help-inline">{{ $errors->has('slug') ? $errors->first('slug') : '' }}</span>
                </div>
            </div>

             <div class="control-group {{ $errors->has('description') ? 'error' : '' }}">
                <label for="description" class="control-label">{{ Lang::line('groups::lang.Description')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::text('description', Input::old('description', '')) }}
                    <span class="help-inline">{{ $errors->has('description') ? $errors->first('description') : '' }}</span>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" name="btnAction" value="save" class="btn btn-primary">
                <span>{{ __('groups::lang.Save')->get(ADM_LANG) }}</span>
            </button>
            <a href="{{ URL::base() .'/'.ADM_URI}}/groups" class="btn">{{ __('groups::lang.Cancel')->get(ADM_LANG) }}</a> 
        </div>
        {{Form::close()}}
    </div>
</div>