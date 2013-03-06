<?php themes\add_asset('field_order.js', 'mod: splashscreen/js', array(), 'footer') ?>
@if(isset($settings))
<div class="row">
    <div style="margin-top:15px;" class="span12">

        {{ Form::open(URL::base().'/'.ADM_URI.'/'.'splashscreen', 'PUT', array('class' => 'form-horizontal')) }}
        <div style="display:none">
            {{ Form::token() }}
        </div>  

        <ul class="nav nav-tabs" id="myTab">
        @foreach($sections as $section)
        <li><a data-toggle="tab" href="#{{ $section }}">{{Str::title(str_replace('_', ' ', $section))}}</a></li>
        @endforeach
        </ul>
        
        <div class="tab-content" style="overflow:visible;">
            @foreach($sections as $section)
                
                <div class="tab-pane" id="{{ $section }}">
                    <div class="ui-sortable">
                        @foreach($this->data['settings'] as $module => $setting)
                            @if($setting->section == $section)
                                @if(isset($setting->type) and !empty($setting->type))
                                    @if($setting->type == 'hidden')
                                        {{ call_user_func("\Form::mwi_field", $setting) }}
                                    @else
                                         @if($setting->slug == 'splashscreen_slug' or $setting->slug == 'splashscreen_flash_news_handler')
                                            <div class="control-group  input-prepend {{ $errors->has($setting->slug) ? 'error' : '' }}">
                                              <label class="control-label" for="{{ $setting->slug }}">{{ Lang::line('splashscreen::lang.'.$setting->title)->get(ADM_LANG) }}</label>
                                              <div class="controls">
                                                <span class="add-on span1">{{ URL::base()}}/</span>
                                                <input type="text" id="{{ $setting->slug }}" name="{{ $setting->slug }}" class="" value="{{ $setting->value }}">
                                                <span class="help-inline">{{ $errors->has($setting->slug) ? $errors->first($setting->slug, '<small style="color:#dd3c10;">:message</small>') : Lang::line('splashscreen::lang.'.$setting->description)->get(ADM_LANG) }}</span>
                                                <span class="move-handle"></span>
                                              </div>
                                            </div>
                                         @else
                                            <div class="control-group {{ $errors->has($setting->slug) ? 'error' : '' }}">
                                            <label for="{{ $setting->slug }}" class="control-label">{{ Lang::line('splashscreen::lang.'.$setting->title)->get(ADM_LANG) }}</label>
                                                <div class="controls">
                                                    {{ call_user_func("\Form::mwi_field", $setting) }}
                                                    @if($setting->type == 'text')
                                                        <span class="required-icon"></span>
                                                    @endif
                                                    <span class="help-inline">{{ $errors->has($setting->slug) ? $errors->first($setting->slug, '<small style="color:#dd3c10;">:message</small>') : Lang::line('splashscreen::lang.'.$setting->description)->get(ADM_LANG) }}</span>
                                                    <span class="move-handle"></span>
                                                </div>
                                            </div>
                                            @endif
                                    @endif
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
                
            @endforeach
        </div>
        
        <div class="form-actions">
                  <button class="btn btn-primary" type="submit">{{ Lang::line('settings::lang.Save changes')->get(ADM_LANG) }}</button>
        </div>
        {{ Form::close() }}
    </div>
</div>

@else
<div class="row">
    <div style="margin-top:15px;" class="offset5">
        <h3>No settings were found.</h3>
    </div>
</div>
@endif

<script>
$(function () {
    $('#myTab a:first').tab('show');
})
</script>