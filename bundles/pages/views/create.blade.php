<?php themes\add_asset('jquery.tagsinput.css', 'jquery', array(), 'header') ?>
<?php themes\add_asset('page-edit.css', 'mod: pages/css', array(), 'header') ?>

<?php themes\add_asset('codemirror.js', 'codemirror', array(), 'footer') ?>
<?php themes\add_asset('jquery.tagsinput.js', 'jquery', array(), 'footer') ?>
<?php themes\add_asset('form.js', 'mod: pages/js', array('scripts'), 'footer') ?>

<?php themes\add_asset('ckeditor.js', 'ckeditor', array(), 'footer') ?>
<?php themes\add_asset('jquery.js', 'ckeditor/adapters', array(), 'footer') ?>

<script type="text/javascript">

    var instance;

    function update_instance()
    {
        instance = CKEDITOR.currentInstance;
    }

    (function($) {
        $(function(){

            mwi.init_ckeditor = function(){
            $('textarea.wysiwyg-advanced').ckeditor({
                toolbar: [
                    ['Maximize'],
                    ['Undo','Redo','-','Find','Replace'],
                    ['Link','Unlink'],
                    ['Table','HorizontalRule','SpecialChar'],
                    ['Bold','Italic','StrikeThrough'],
                    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl'],
                    ['Format', 'FontSize', 'Subscript','Superscript', 'NumberedList','BulletedList','Outdent','Indent','Blockquote'],
                    ['ShowBlocks', 'RemoveFormat', 'Source']
                ],
                width: '90%',
                height: 400,
                dialog_backgroundCoverColor: '#000',
                removePlugins: 'elementspath',
                defaultLanguage: 'en',
                language: 'en'
            });         
        };

        mwi.init_ckeditor();
    });

})(jQuery);

</script>

<div class="row">
   {{ Form::open(URL::base().'/'.ADM_URI.'/pages', 'POST', array('class' => 'form-horizontal', 'id' => 'page-form-new')) }}

  <div style="display:none">
    {{ Form::token() }}
    {{ Form::hidden('parent_id', 0) }}
  </div>
<div class="tabbable span12">

  <ul class="nav nav-tabs" id="edit-page-tabs">
    <li class="active"><a data-toggle="tab" href="#page_content">{{ Lang::line('pages::lang.Page Content')->get(ADM_LANG) }}</a></li>
    <li><a data-toggle="tab" href="#meta_data">{{Lang::line('pages::lang.Meta Data')->get(ADM_LANG)}}</a></li>
    <li><a data-toggle="tab" href="#options">{{Lang::line('pages::lang.Options')->get(ADM_LANG)}}</a></li>
  </ul>
  <div class="tab-content">
    
    <div class="tab-pane active" id="page_content">
      
      <div class="control-group {{ $errors->has('title') ? 'error' : '' }}">
          {{ Form::label('title', Lang::line('pages::lang.Title')->get(ADM_LANG), array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::text('title', Input::old('title'), array('class' => '')) }}
            <span class="help-inline">{{ $errors->has('title') ? $errors->first('title') : '' }}</span>
          </div>
        </div>

        <div class="control-group {{ $errors->has('slug') ? 'error' : '' }} input-prepend">
          {{ Form::label('slug', Lang::line('pages::lang.Slug')->get(ADM_LANG), array('class' => 'control-label')) }}
          <div class="controls">
            <span class="add-on span1">{{URL::base()}}/</span>
            {{ Form::text('slug', Input::old('slug'), array('class' => '')) }}
            <span class="help-inline">{{ $errors->has('slug') ? $errors->first('slug') : '' }}</span>
          </div>
        </div>

        <div class="control-group {{ $errors->has('status') ? 'error' : '' }}">
          {{ Form::label('status', Lang::line('pages::lang.Status')->get(ADM_LANG), array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::select('status', array('draft' => Lang::line('pages::lang.Draft')->get(ADM_LANG), 'live' => Lang::line('pages::lang.Live')->get(ADM_LANG)), 'live') }}
            <span class="help-inline">{{ $errors->has('status') ? $errors->first('status') : '' }}</span>
          </div>
        </div>

        <?php $navigation_options = array('0' => Lang::line('pages::lang.-- None --')->get(ADM_LANG)); ?>
        <?php foreach($navigation_groups as $navigation_group): ?>
        <?php $navigation_options[$navigation_group->id] = $navigation_group->title; ?>
        <?php endforeach ?>
        <div class="control-group {{ $errors->has('navigation_group_id') ? 'error' : '' }}">
          {{ Form::label('navigation_group_id', Lang::line('pages::lang.Add to Navigation')->get(ADM_LANG), array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::select('navigation_group_id', $navigation_options) }}
            <span class="help-inline">{{ $errors->has('navigation_group_id') ? $errors->first('navigation_group_id') : '' }}</span>
          </div>
        </div>
                           
        <div class="control-group {{ $errors->has('body') ? 'error' : '' }}">
          {{ Form::label('body', Lang::line('pages::lang.Body')->get(ADM_LANG), array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::textarea('body', Input::old('body'), array('class' => 'wysiwyg-advanced')) }}
            <span class="help-inline">{{ $errors->has('body') ? $errors->first('body') : '' }}</span>
          </div>
        </div>
      </div>

    <div class="tab-pane" id="meta_data">
      <div class="control-group {{ $errors->has('meta_title') ? 'error' : '' }}">
          {{ Form::label('meta_title', Lang::line('pages::lang.Meta Title')->get(ADM_LANG), array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::text('meta_title', Input::old('meta_title'), array('class' => 'span4')) }}
            <span class="help-inline">{{ $errors->has('meta_title') ? $errors->first('meta_title') : '' }}</span>
          </div>
        </div>

        <div class="control-group {{ $errors->has('meta_keywords') ? 'error' : '' }}">
          {{ Form::label('meta_keywords', Lang::line('pages::lang.Meta Keywords')->get(ADM_LANG), array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::text('meta_keywords', Input::old('meta_keywords')) }}
            <span class="help-inline">{{ $errors->has('meta_keywords') ? $errors->first('meta_keywords') : '' }}</span>
          </div>
        </div>

        <div class="control-group {{ $errors->has('meta_description') ? 'error' : '' }}">
          {{ Form::label('meta_description', Lang::line('pages::lang.Meta Description')->get(ADM_LANG), array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::text('meta_description', Input::old('meta_description'), array('class' => 'span4')) }}
            <span class="help-inline">{{ $errors->has('meta_description') ? $errors->first('meta_description') : '' }}</span>
          </div>
        </div>
    </div>

    <div class="tab-pane" id="options">
      <div class="control-group {{ $errors->has('restricted_to') ? 'error' : '' }}">
          {{ Form::label('restricted_to[]', Lang::line('pages::lang.Access')->get(ADM_LANG), array('class' => 'control-label')) }}
          <div class="controls">
            <select name="restricted_to[]" size="3" multiple="multiple">
              <option value="0" selected="selected">{{Lang::line('pages::lang.-- Any --')->get(ADM_LANG)}}</option>
              <option value="-1">{{ Lang::line('navigation::lang.Guests')->get(ADM_LANG) }}</option>
              <option value="-2">{{ Lang::line('navigation::lang.Authenticated')->get(ADM_LANG) }}</option>
                    
              @foreach($groups as $group)
              <option value="{{ $group->id }}">{{ $group->name }}</option>
              @endforeach
            </select>
            <span class="help-inline">{{ $errors->has('restricted_to') ? $errors->first('restricted_to') : '' }}</span>
          </div>
        </div>

        <div class="control-group {{ $errors->has('is_home') ? 'error' : '' }}">
          {{ Form::label('is_home', Lang::line('pages::lang.Is default (home) page?')->get(ADM_LANG), array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::checkbox('is_home', Input::old('is_home'), false) }}
            <span class="help-inline">{{ $errors->has('is_home') ? $errors->first('is_home') : '' }}</span>
          </div>
        </div>

        <div class="control-group {{ $errors->has('strict_uri') ? 'error' : '' }}">
          {{ Form::label('strict_uri', Lang::line('pages::lang.Require an exact uri match?')->get(ADM_LANG), array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::checkbox('strict_uri', Input::old('strict_uri'), true) }}
            <span class="help-inline">{{ $errors->has('strict_uri') ? $errors->first('strict_uri') : '' }}</span>
          </div>
        
        </div>
      
      </div>

  </div>
  
<div class="form-actions">
      <button type="submit" name="btnAction" value="save" class="btn btn-primary">
          <span>{{ Lang::line('pages::lang.Create')->get(ADM_LANG) }}</span>
      </button>
      <a href="{{ URL::base().'/'.ADM_URI.'/'}}pages" class="btn cancel">{{ Lang::line('pages::lang.Cancel')->get(ADM_LANG) }}</a>
    </div>
</div>

{{ Form::close() }}
</div>