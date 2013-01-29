<?php themes\add_asset('jquery-ui.css', 'jquery', array(), 'header') ?>
<?php themes\add_asset('jquery.tagit.css', 'mod: settings/css', array(), 'header') ?>
<?php themes\add_asset('tagit.ui-zendesk.css', 'mod: settings/css', array(), 'header') ?>
<?php themes\add_asset('tag-it.js', 'mod: settings/js', array('scripts'), 'footer') ?>
<?php themes\add_asset('keywords.js', 'mod: settings/js', array('scripts'), 'footer') ?>
<style>
.controls ul {margin-left: 10px;}
</style>
<script>
    var keywords = {{ json_encode(explode(',', $keywords)) }};
</script>

<div class="row">
    <div style="margin-top:15px;" class="span12">
    {{ Form::open(URL::base().'/'.ADM_URI.'/'.'settings/keywords', 'PUT', array('class' => 'form-horizontal')) }}
        <div style="display:none">
            {{ Form::token() }}
        </div>
            <div class="ui-sortable">
                <div class="control-group {{ $errors->has('keywords') ? 'error' : '' }}">
                    <label for="keywords" class="control-label">{{ Lang::line('settings::lang.Application Keywords')->get(ADM_LANG) }}</label>
                    <div class="controls">
                       <input name="application_keywords" id="application_keywords" value="{{ $keywords }}">
                        
                        <span class="help-inline">{{ $errors->has('keywords') ? $errors->first('keywords', '<small style="color:#dd3c10;">:message</small>') : '' }}</span>
                    </div>
                </div>   
            </div>

        <div class="form-actions">
              <button class="btn btn-primary" type="submit">{{ Lang::line('settings::lang.Save changes')->get(ADM_LANG) }}</button>
        </div>
    {{ Form::close() }}
</div>
</div>