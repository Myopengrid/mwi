<?php themes\add_asset('jquery.colorbox.js', 'jquery', array('jquery'), 'footer') ?>
<?php themes\add_asset('ckeditor.js', 'ckeditor', array(), 'footer') ?>
<?php themes\add_asset('jquery.js', 'ckeditor/adapters', array(), 'footer') ?>
<?php themes\add_asset('mwi_ckeditor.js', 'ckeditor', array(), 'footer') ?>

<div style="margin-top:30px;" class="row">
    <div class="offset5 span4"> 
        <a data-html="true" class="module-description-info" href="#" data-content="{{ __('email::lang.The default system variables available for your email template are:')->get(ADM_LANG) }}<br />
            &#123;&#123; settings:site_name &#125;&#125;<br />
            &#123;&#123; url:base &#125;&#125;<br />
            &#123;&#123; user:username &#125;&#125;<br />
            &#123;&#123; user:avatar_first_name &#125;&#125;<br />
            &#123;&#123; user:avatar_last_name &#125;&#125;<br />
            &#123;&#123; request:ip &#125;&#125;<br />
            &#123;&#123; request:user_agent &#125;&#125;<br />
            &#123;&#123; request:languages &#125;&#125;<br />" data-placement="bottom" rel="popover" data-original-title="Email Template Variables">
            View Template Variables
        </a>
    </div>
    
    <div class="span12">
        {{ Form::open(URL::base().'/'.ADM_URI.'/email/template/'.$template->id, 'PUT', array('class' => 'form-horizontal')) }}
        <div style="display:none">
        {{ Form::token() }}
        </div>
        <div class="draggable-field ui-sortable">

            <div class="control-group {{ $errors->has('name') ? 'error' : '' }}">
                <label class="control-label" for="name">{{ Lang::line('email::lang.Name')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::text('name', $template->name) }}
                    <span class="required-icon"></span>
                    @if(isset($errors) and $errors->has('name'))
                    <span class="help-inline">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>

            <div class="control-group {{ $errors->has('slug') ? 'error' : '' }}">
                <label class="control-label" for="slug">{{ Lang::line('email::lang.Slug')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::text('slug', $template->slug) }}
                    <span class="required-icon"></span>
                    @if(isset($errors) and $errors->has('slug'))
                    <span class="help-inline">{{ $errors->first('slug') }}</span>
                    @endif
                </div>
            </div>

            <div class="control-group {{ $errors->has('lang') ? 'error' : '' }}">
                <label class="control-label" for="lang">{{ Lang::line('email::lang.Language')->get(ADM_LANG) }}</label>
                <div class="controls">
                    <?php $langs = json_decode(Settings\Config::get('settings::core.available_languages'), true); ?>
                    {{ Form::select('lang', $langs, $template->lang) }}
                    @if(isset($errors) and $errors->has('lang'))
                    <span class="help-inline">{{ $errors->first('lang') }}</span>
                    @endif
                </div>
            </div>

            <div class="control-group {{ $errors->has('type') ? 'error' : '' }}">
                <label class="control-label" for="type">{{ Lang::line('email::lang.Type')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::select('type', array('html' => 'HTML', 'text' => Lang::line('email::lang.Plain Text')->get(ADM_LANG)), $template->lang) }}
                    @if(isset($errors) and $errors->has('type'))
                    <span class="help-inline">{{ $errors->first('type') }}</span>
                    @endif
                </div>
            </div>

            <div class="control-group {{ $errors->has('description') ? 'error' : '' }}">
                <label class="control-label" for="description">{{ Lang::line('email::lang.Description')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::text('description', $template->description) }}
                    @if(isset($errors) and $errors->has('description'))
                    <span class="help-inline">{{ $errors->first('description') }}</span>
                    @endif
                </div>
            </div>

            <div class="control-group {{ $errors->has('subject') ? 'error' : '' }}">
                <label class="control-label" for="subject">{{ Lang::line('email::lang.Subject')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::text('subject', $template->subject) }}
                    <span class="required-icon"></span>
                    @if(isset($errors) and $errors->has('subject'))
                    <span class="help-inline">{{ $errors->first('subject') }}</span>
                    @endif
                </div>
            </div>

            <div class="control-group {{ $errors->has('body') ? 'error' : '' }}">
                <label class="control-label" for="body">{{ Lang::line('email::lang.Body')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::textarea('body', $template->body, array('class' => 'templates wysiwyg-advanced')) }}
                    @if(isset($errors) and $errors->has('body'))
                    <span class="help-inline">{{ $errors->first('body') }}</span>
                    @endif
                </div>
            </div>

            <div class="form-actions">
              <button class="btn btn-primary" type="submit">{{ Lang::line('email::lang.Save')->get(ADM_LANG) }}</button>
            </div>
        </div>
    {{ Form::close() }}
    </div>
</div>     