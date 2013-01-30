<?php themes\add_asset('ckeditor.js', 'ckeditor', array(), 'footer') ?>
<?php themes\add_asset('jquery.tagsinput.js', 'jquery', array(), 'footer') ?>
<?php themes\add_asset('jquery.tagsinput.css', 'jquery', array(), 'footer') ?>
<?php themes\add_asset('get_users.js', 'mod: email/js', array(), 'footer') ?>
<script>
// Try to repopulate selected users
// if this is a failed post
<?php $old_post = json_encode(Input::old('only_emails', array()), true); ?>
var OLD_POST_EMAILS = "{{$old_post}}";
</script>

<div class="row">
    <div class="span12">
    
    <fieldset id="filters">
        <legend>{{ __('email::lang.Filter users to receive the email')->get(ADM_LANG) }}</legend>
        {{ Form::open(URL::base().'/'.ADM_URI.'/email', 'POST', array('class' => 'form-horizontal', 'name' => 'send_email_form')) }}

        <div style="display:none">
            {{Form::token()}}
        </div>  
            <ul>
                <li>
                    <label for="status">{{ __('email::lang.Status')->get(ADM_LANG) }}</label>
                    <select name="status" class="chzn-filters">
                        <?php $old_status_input = Input::old('status', 'active') ?>
                        <?php $status_dropdown = array('active' => __('email::lang.Active')->get(ADM_LANG), 'inactive' => __('email::lang.Inactive')->get(ADM_LANG), 'banned' => __('email::lang.Banned')->get(ADM_LANG)); ?>
                        <option value="0">{{ __('email::lang.-- All --')->get(ADM_LANG) }}</option>
                        <?php foreach ($status_dropdown as $value => $item):?>
                        <option <?php echo ($old_status_input == $value) ? ' selected="selected"' : '' ?> value="{{ $value }}">{{ $item }}</option>
                        <?php endforeach ?>
                    </select>
                    {{ $errors->has('status') ? $errors->first('status', '<small style="color:#dd3c10;">:message</small>') : '' }}
                </li>
                
                @if(\Bundle::exists('groups'))
                <li>
                    <label for="group">{{ __('email::lang.Group')->get(ADM_LANG) }}</label>          
                    <select name="group" class="chzn-filters">
                        <?php $old_group_input = Input::old('group', '0') ?>
                        <option value="0">{{ __('email::lang.-- All --')->get(ADM_LANG) }}</option>
                        @if(isset($groups_dropdown) and !empty($groups_dropdown))
                        <?php foreach($groups_dropdown as $group): ?>
                        <option <?php echo ($old_group_input == $group->id) ? ' selected="selected"' : '' ?> value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                        @endif
                    </select>
                    {{ $errors->has('group') ? $errors->first('group', '<small style="color:#dd3c10;">:message</small>') : '' }}
                </li>
                @endif

                <li>
                    <label for="template"> {{ __('email::lang.Use Email Template')->get(ADM_LANG) }}</label>
                    <select id="template" name="template">
                        <?php $old_template_input = Input::old('template', '0') ?>
                        <option value="0" selected="selected">{{ __('email::lang.-- No --')->get(ADM_LANG) }}</option>
                        @if(isset($email_templates) and !empty($email_templates))
                        <?php foreach($email_templates as $template): ?>
                        <option <?php echo ($old_template_input == $template->id) ? ' selected="selected"' : '' ?> value="{{ $template->id }}">{{ $template->name.' ('.$template->lang.')' }}</option>
                        @endforeach
                        @endif
                    </select>
                    {{ $errors->has('template') ? $errors->first('template', '<small style="color:#dd3c10;">:message</small>') : '' }}
                </li>
            </ul>
        
    </fieldset>
    <div class="form_inputs">
        <ul>
            <li class="ajax-emails">
                <label for="only_emails[]">{{ __('email::lang.Select Users')->get(ADM_LANG) }}:
                    <small>{{ __('email::lang.You can deep filter here who will receive the message; You can leave this field empty and the message will be sent using criteria from filter above')->get(ADM_LANG) }}</small>
                </label>
                <select multiple="multiple" data-placeholder="{{ __('email::lang.Please use filters above to populate this field')->get(ADM_LANG) }}" style="width:530px" size="3" id="only_emails" name="only_emails[]" class="chzn-select only-emails">
                </select>
                {{ $errors->has('only_emais') ? $errors->first('only_emails', '<small style="color:#dd3c10;">:message</small>') : '' }}
            </li>

            <li class="even">
                <label for="alt_emails">{{ __('email::lang.Custom Emails')->get(ADM_LANG) }}:
                    <small>{{ __('email::lang.If you want to add more emails here please insert them separated by comma')->get(ADM_LANG) }}</small>
                </label>
                <div class="input">
                    <input type="text" style="width:500px" value="{{ Input::old('alt_emails', '') }}" name="alt_emails">
                    {{ $errors->has('alt_emails') ? $errors->first('alt_emails', '<small style="color:#dd3c10;">:message</small>') : '' }}
                </div>
            </li>

            <li class="even custom-email">
                <label for="subject">
                    {{ __('email::lang.Subject')->get(ADM_LANG) }}
                    <small>{{ __('email::lang.Please enter email subject')->get(ADM_LANG) }}</small>
                </label>
                <div class="input">
                    <input type="text" style="width:500px" value="{{ Input::old('subject', '') }}" name="subject">
                    {{ $errors->has('subject') ? $errors->first('subject', '<small style="color:#dd3c10;">:message</small>') : '' }}
                </div>
            </li>
            <li class="custom-email">
                <label for="email_type">
                    {{ __('email::lang.Send Email As')->get(ADM_LANG) }}
                    <small>{{ __('email::lang.Please specify the format that this email should be sent')->get(ADM_LANG) }}</small>
                </label>
                <div class="input">
                    <select name="email_type">
                        <option selected="selected" value="html">{{ __('email::lang.HTML')->get(ADM_LANG) }}</option>
                        <option value="text">{{ __('email::lang.Plain Text')->get(ADM_LANG) }}</option>
                    </select>
                    {{ $errors->has('email_type') ? $errors->first('email_type', '<small style="color:#dd3c10;">:message</small>') : '' }}
                </div>
            </li>

            <li class="custom-email">
                <label for="email_body">
                    {{ __('email::lang.Email Message')->get(ADM_LANG) }}
                </label>
                <br style="clear:both">
                <span class="">
                    <textarea style="width: 98%;" class="ckeditor edit-area" rows="20" cols="40" name="email_body">{{ Input::old('email_body', '') }}</textarea>
                    {{ $errors->has('email_body') ? $errors->first('email_body', '<small style="color:#dd3c10;">:message</small>') : '' }}
                </span>
            </li>
        </ul>
    </div>
    <div class="form-actions">
        <button class="btn btn-primary" value="save" name="btnAction" type="submit">
            <span>{{ __('email::lang.Send')->get(ADM_LANG) }}</span>
        </button>
        <a class="btn gray cancel" href="{{ URL::base().'/'.ADM_URI }}/email/default_templates">{{ __('email::lang.Cancel')->get(ADM_LANG) }}</a>
    </div>
    {{ Form::close() }}
    </div>
</div>