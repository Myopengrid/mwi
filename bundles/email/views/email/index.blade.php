<?php themes\add_asset('settings.js', 'mod: email/js', array(), 'footer') ?>

<div class="row">
    <div class="span12">
        {{ Form::open( URL::base().'/'.ADM_URI .'/email', 'PUT', array('class' => 'form-horizontal')) }}
            <div style="display:none">
                {{ Form::token() }}
            </div>

            <div class="draggable-fields" id="main">
                @foreach($this->data['setting_section'] as $module => $setting)
                    @if(isset($setting->type) and !empty($setting->type))
                        <div class="{{ $setting->class }} control-group {{ $errors->has($setting->slug) ? 'error' : '' }}">
                            <label for="name" class="control-label">{{ __('email::lang.'.$setting->title)->get(ADM_LANG) }}</label>
                            <div class="controls">
                            @if($setting->type == 'select')
                            {{ call_user_func("Form::select", $setting->slug, json_decode($setting->options, true), Input::old($setting->slug, $setting->value) , array('id' => $setting->slug, 'class' => $setting->class)) }}
                            @else
                            {{ call_user_func("Form::input", $setting->type, $setting->slug, $setting->value, array('id' => $setting->slug, 'class' => $setting->class)) }}
                                <span class="required-icon"></span>
                                @if(isset($errors) and $errors->has($setting->slug))
                                <span class="help-inline">{{ $errors->first($setting->slug) }}</span>
                                @endif
                            @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        
            <div class="form-actions">
                <button type="submit" name="btnAction" class="btn btn-primary">
                    <span>{{ __('email::lang.Save')->get(ADM_LANG) }}</span>
                </button>
            </div>
        {{ Form::close() }}
    </div>
</div>      