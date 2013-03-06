<?php themes\add_asset('ckeditor.js', 'ckeditor', array(), 'footer') ?>
<?php themes\add_asset('jquery.js', 'ckeditor/adapters', array(), 'footer') ?>
<?php themes\add_asset('mwi_ckeditor.js', 'ckeditor', array('scripts'), 'footer') ?>

@if(isset($news))
<div style="margin-top:30px;" class="row">
    <div class="span12">
        {{ Form::open(URL::base().'/'.ADM_URI.'/splashscreen/flash_news/'.$news->id, 'PUT', array('class' => 'form-horizontal')) }}
        <div style="display:none">
        {{ Form::token() }}
        </div>
        <div class="draggable-field ui-sortable">
            <div class="control-group {{ $errors->has('name') ? 'error' : '' }}">
                <label class="control-label" for="name">{{ Lang::line('splashscreen::flashnews.Title')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::text('name', $news->title) }}
                    <span class="required-icon"></span>
                    @if(isset($errors) and $errors->has('name'))
                    <span class="help-inline">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>

            <div class="control-group {{ $errors->has('slug') ? 'error' : '' }}">
              <label class="control-label" for="slug">{{ Lang::line('splashscreen::lang.Handler')->get(ADM_LANG) }}</label>
              <div class="controls">
                <a class="btn" href="{{ URL::base()}}/flash_news/{{$news->slug}}">{{ URL::base()}}/flash_news/{{$news->slug}}</a>
              </div>
            </div>

            <div class="control-group {{ $errors->has('is_enabled') ? 'error' : '' }}">
                <label class="control-label" for="is_enabled">{{ Lang::line('splashscreen::flashnews.Status')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::select('is_enabled', array('1' => 'Published', '0' => 'Draft'), $news->is_enabled) }}
                    @if(isset($errors) and $errors->has('is_enabled'))
                    <span class="help-inline">{{ $errors->first('is_enabled') }}</span>
                    @endif
                </div>
            </div>

            <div class="control-group {{ $errors->has('message') ? 'error' : '' }}">
                <label class="control-label" for="message">{{ Lang::line('splashscreen::flashnews.Message')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::textarea('message', $news->message, array('class' => 'wysiwyg-advanced')) }}
                    @if(isset($errors) and $errors->has('message'))
                    <span class="help-inline">{{ $errors->first('message') }}</span>
                    @endif
                </div>
            </div>

            <div class="form-actions">
              <button class="btn btn-primary" type="submit">{{ Lang::line('splashscreen::flashnews.Save')->get(ADM_LANG) }}</button>
            </div>
        </div>
    {{ Form::close() }}
    </div>
</div>
@else
<div class="item">
    <div style="margin:20px" class="offset5">
        <h3>{{ __('splashscreen::flashnews.The flash news to edit was not found')->get(ADM_LANG) }}</h3>
    </div>
</div>
@endif     