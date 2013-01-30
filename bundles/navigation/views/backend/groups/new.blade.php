<div class="row">
    <div class="span12">
        {{ Form::open(URL::base().'/'.ADM_URI.'/navigation/groups', 'POST', array('class' => 'form-horizontal')) }}
        <div style="display:none">
        {{ Form::token() }}
        </div>
        
        <div class="control-group {{ $errors->has('title') ? 'error' : '' }}">
            <label for="title" class="control-label">{{ Lang::line('navigation::lang.Title')->get(ADM_LANG) }}</label>
            <div class="controls">
                {{ Form::text('title', Input::old('title')) }}
                <span class="required-icon"></span>
                <span class="help-inline">{{ $errors->has('title') ? $errors->first('title', '<small style="color:#dd3c10;">:message</small>') : '' }}</span>
            </div>
        </div>

        <div class="control-group {{ $errors->has('slug') ? 'error' : '' }}">
            <label for="slug" class="control-label">{{ Lang::line('navigation::lang.Slug')->get(ADM_LANG) }}</label>
            <div class="controls">
                {{ Form::text('slug', Input::old('slug')) }}
                <span class="required-icon"></span>
                <span class="help-inline">{{ $errors->has('slug') ? $errors->first('slug', '<small style="color:#dd3c10;">:message</small>') : '' }}</span>
            </div>
        </div>

        <div class="form-actions">
            <button class="btn btn-primary" value="save" name="btnAction" type="submit">
                <span>{{ Lang::line('navigation::lang.Save')->get(ADM_LANG) }}</span>
            </button>

            <a class="btn" href="{{URL::base().DS.ADM_URI}}/navigation">{{ Lang::line('navigation::lang.Cancel')->get(ADM_LANG) }}</a> 
        </div>
    {{ Form::close() }}  
    </div>  
</div>
    
            