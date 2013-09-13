<?php themes\add_asset('jquery.tagsinput.css', 'jquery', array(), 'header') ?>
<?php themes\add_asset('page-edit.css', 'mod: pages/css', array(), 'header') ?>

<?php themes\add_asset('codemirror.js', 'codemirror', array(), 'footer') ?>
<?php themes\add_asset('jquery.tagsinput.js', 'jquery', array(), 'footer') ?>
<?php themes\add_asset('form.js', 'mod: pages/css', array('scripts'), 'footer') ?>

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

<div class="item">

  <ul class="nav nav-tabs" id="edit-page-tabs">
    <li class="active"><a data-toggle="tab" href="#page_content">Page Content</a></li>
    <li><a data-toggle="tab" href="#meta_data">Meta Data</a></li>
    <li><a data-toggle="tab" href="#options">Options</a></li>
  </ul>

  {{ Form::open( URL::base().'/'.ADM_URI.'/pages', 'POST', array('class' => 'form-horizontal', 'id' => 'page-form')) }}

    <div style="display:none">
      {{ Form::token() }}
      {{ Form::hidden('parent_id', 0) }}
    </div>

    <div class="tab-content" id="edit-page-tab-content">

      <div id="page_content" class="tab-pane fade in active">

        <div class="control-group {{ $errors->has('title') ? 'error' : '' }}">
          {{ Form::label('title', 'Title', array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::text('title', Input::old('title'), array('class' => 'span3')) }}
            <span class="help-inline">{{ $errors->has('title') ? $errors->first('title') : '' }}</span>
          </div>
        </div>

        <div class="control-group input-prepend">
          {{ Form::label('slug', 'Slug', array('class' => 'control-label')) }}
          <div class="controls">
            <span class="add-on span1">{{URL::base()}}/page/</span>
            {{ Form::text('slug', Input::old('slug'), array('class' => 'span2')) }}
            <span class="help-inline">{{ $errors->has('slug') ? $errors->first('slug') : '' }}</span>
          </div>
        </div>

        <div class="control-group">
          {{ Form::label('status', 'Status', array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::select('status', array('draft' => 'Draft', 'live' => 'Live'), $page->status) }}
            <span class="help-inline">{{ $errors->has('status') ? $errors->first('status') : '' }}</span>
          </div>
        </div>


        <div class="control-group">
            {{ Form::label('navigation_group_id', 'Add to Navigation', array('class' => 'control-label')) }}
            <div class="controls">
            <select name="navigation_group_id">
                <option value="0">-- None --</option>
                @foreach($navigation_groups as $navigation_group)
                <option value="{{ $navigation_group->id }}">{{ $navigation_group->title }}</option>
                @endforeach
            </select>
             <span class="help-inline">{{ $errors->has('navigation_group_id') ? $errors->first('navigation_group_id') : '' }}</span>
            </div>
        </div>

        <div class="control-group {{ $errors->has('body') ? 'error' : '' }}">
          {{ Form::label('body', 'Body', array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::textarea('body', $page->body, array('class' => 'wysiwyg-advanced')) }}
            <span class="help-inline">{{ $errors->has('body') ? $errors->first('body') : '' }}</span>
          </div>
        </div>

      </div>

      <div id="meta_data" class="tab-pane fade">

        <div class="control-group">
          {{ Form::label('meta_title', 'Meta Title', array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::text('meta_title', $page->meta_title, array('class' => 'span4')) }}
            <span class="help-inline">{{ $errors->has('meta_title') ? $errors->first('meta_title') : '' }}</span>
          </div>
        </div>

        <div class="control-group">
          {{ Form::label('meta_keywords', 'Meta Keywords', array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::text('meta_keywords', $page->meta_keywords) }}
            <span class="help-inline">{{ $errors->has('meta_keywords') ? $errors->first('meta_keywords') : '' }}</span>
          </div>
        </div>

        <div class="control-group">
          {{ Form::label('meta_description', 'Meta Description', array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::text('meta_description', $page->meta_description, array('class' => 'span4')) }}
            <span class="help-inline">{{ $errors->has('meta_description') ? $errors->first('meta_description') : '' }}</span>
          </div>
        </div>
        
      </div>

      <div id="options" class="tab-pane fade">

        <?php $groups_id = explode(',', $page->restricted_to); ?>
        
        <div class="control-group">
          {{ Form::label('restricted_to[]', 'Access', array('class' => 'control-label')) }}
          <div class="controls">
            <select name="restricted_to[]" size="3" multiple="multiple">
              <?php $selected_any = in_array("0", $groups_id) ?  'selected="selected"' : ''; ?>
              <option value="0" {{ $selected_any }}>-- Any --</option>
              @foreach($groups as $group)
              <?php $selected = in_array($group->id, $groups_id) ? 'selected="selected"' : ''; ?>
              <option {{ $selected }} value="{{ $group->id }}">{{ $group->name }}</option>
              @endforeach
            </select>
            <span class="help-inline">{{ $errors->has('restricted_to') ? $errors->first('restricted_to') : '' }}</span>
          </div>
        </div>

        <div class="control-group">
          {{ Form::label('is_home', 'Is default (home) page?', array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::checkbox('is_home', 1, (bool)$page->is_home) }}
            <span class="help-inline">{{ $errors->has('is_home') ? $errors->first('is_home') : '' }}</span>
          </div>
        </div>

        <div class="control-group">
          {{ Form::label('strict_uri', 'Require an exact uri match?', array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::checkbox('strict_uri', 1, (bool)$page->strict_uri) }}
            <span class="help-inline">{{ $errors->has('strict_uri') ? $errors->first('strict_uri') : '' }}</span>
          </div>
        </div>

      </div>

    </div>
    
    <div class="form-actions">
      <button type="submit" name="btnAction" value="save" class="btn btn-primary">
          <span>Create</span>
      </button>
      <a href="{{ URL::base().'/'.ADM_URI.'/'}}pages" class="btn cancel">Cancel</a>
    </div>

  </form>
</div>
