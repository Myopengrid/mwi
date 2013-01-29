
<?php themes\add_asset('settings.js', 'mod: settings/js', array('scripts'), 'footer') ?>

<div class="row">
    <div style="margin-top:15px;" class="span12">
    {{ Form::open(URL::base().'/'.ADM_URI.'/'.'settings', 'PUT', array('class' => 'form-horizontal')) }}
        <div style="display:none">
            {{ Form::token() }}
        </div>
            
            <div class="ui-sortable">
                @foreach($this->data['settings'] as $module => $setting)
                @if(isset($setting->type) and !empty($setting->type))
                    @if($setting->type == 'hidden')
                    {{ call_user_func("\Form::mwi_field", $setting) }}
                    @else
                        <div class="control-group {{ $errors->has($setting->slug) ? 'error' : '' }}">
                            <label for="{{ $setting->slug }}" class="control-label">{{ Lang::line('settings::lang.'.$setting->title)->get(ADM_LANG) }}</label>
                            <div class="controls">
                                {{ call_user_func("\Form::mwi_field", $setting) }}
                                @if($setting->type == 'text')
                                <span class="required-icon"></span>
                                @endif
                                <span class="help-inline">{{ $errors->has($setting->slug) ? $errors->first($setting->slug, '<small style="color:#dd3c10;">:message</small>') : '' }}</span>
                                <span class="move-handle"></span>
                            </div>
                        </div>
                    @endif
                @endif
                @endforeach
            </div>

        <div class="form-actions">
              <button class="btn btn-primary" type="submit">{{ Lang::line('settings::lang.Save changes')->get(ADM_LANG) }}</button>
        </div>
    {{ Form::close() }}
</div>
</div>