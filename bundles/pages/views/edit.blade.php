<?php themes\add_asset('codemirror.js', 'codemirror', array(), 'footer') ?>

<?php themes\add_asset('jquery.tagsinput.css', 'jquery', array(), 'header') ?>
<?php themes\add_asset('page-edit.css', 'mod: pages/css', array(), 'header') ?>

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

<div class="item">

  <ul class="nav nav-tabs" id="edit-page-tabs">
    <li class="active"><a data-toggle="tab" href="#page_content">{{ Lang::line('pages::lang.Page Content')->get(ADM_LANG) }}</a></li>
    <li><a data-toggle="tab" href="#meta_data">{{ Lang::line('pages::lang.Meta Data')->get(ADM_LANG) }}</a></li>
    <li><a data-toggle="tab" href="#options">{{ Lang::line('pages::lang.Options')->get(ADM_LANG) }}</a></li>
  </ul>

  {{ Form::open(URL::base().'/'.ADM_URI.'/pages/'.$page->id, 'PUT', array('class' => 'form-horizontal', 'id' => 'page-form')) }}

    <div style="display:none">
      {{ Form::token() }}
      {{ Form::hidden('parent_id', 0) }}
    </div>

    <div class="tab-content" id="edit-page-tab-content">

      <div id="page_content" class="tab-pane fade in active">

        <div class="control-group {{ $errors->has('title') ? 'error' : '' }}">
          {{ Form::label('title', Lang::line('pages::lang.Title')->get(ADM_LANG), array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::text('title', $page->title, array('class' => 'span3')) }}
            <span class="help-inline">{{ $errors->has('title') ? $errors->first('title') : '' }}</span>
          </div>
        </div>

        <div class="control-group input-prepend">
          {{ Form::label('slug', Lang::line('pages::lang.Slug')->get(ADM_LANG), array('class' => 'control-label')) }}
          <div class="controls">
            <span class="add-on span1">{{URL::base()}}/</span>
            {{ Form::text('slug', $page->slug, array('class' => 'span2')) }}
            <span class="help-inline">{{ $errors->has('slug') ? $errors->first('slug') : '' }}</span>
          </div>
        </div>

        <div class="control-group">
          {{ Form::label('status', Lang::line('pages::lang.Status')->get(ADM_LANG), array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::select('status', array('draft' => Lang::line('pages::lang.Draft')->get(ADM_LANG), 'live' => Lang::line('pages::lang.Live')->get(ADM_LANG)), $page->status) }}
            <span class="help-inline">{{ $errors->has('status') ? $errors->first('status') : '' }}</span>
          </div>
        </div>

        <div class="control-group {{ $errors->has('body') ? 'error' : '' }}">
          {{ Form::label('body', Lang::line('pages::lang.Body')->get(ADM_LANG), array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::textarea('body', $page->body, array('class' => 'wysiwyg-advanced')) }}
            <span class="help-inline">{{ $errors->has('body') ? $errors->first('body') : '' }}</span>
          </div>
        </div>

      </div>

      <div id="meta_data" class="tab-pane fade">

        <div class="control-group">
          {{ Form::label('meta_title', Lang::line('pages::lang.Meta Title')->get(ADM_LANG), array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::text('meta_title', $page->meta_title, array('class' => 'span4')) }}
            <span class="help-inline">{{ $errors->has('meta_title') ? $errors->first('meta_title') : '' }}</span>
          </div>
        </div>

        <div class="control-group">
          {{ Form::label('meta_keywords', Lang::line('pages::lang.Meta Keywords')->get(ADM_LANG), array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::text('meta_keywords', $page->meta_keywords) }}
            <span class="help-inline">{{ $errors->has('meta_keywords') ? $errors->first('meta_keywords') : '' }}</span>
          </div>
        </div>

        <div class="control-group">
          {{ Form::label('meta_description', Lang::line('pages::lang.Meta Description')->get(ADM_LANG), array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::text('meta_description', $page->meta_description, array('class' => 'span4')) }}
            <span class="help-inline">{{ $errors->has('meta_description') ? $errors->first('meta_description') : '' }}</span>
          </div>
        </div>
        
      </div>
      
      <div id="options" class="tab-pane fade">

        <?php $groups_id = explode(',', $page->restricted_to); ?>
        
        <div class="control-group">
          {{ Form::label('restricted_to[]', Lang::line('pages::lang.Access')->get(ADM_LANG), array('class' => 'control-label')) }}
          <div class="controls">
            <select name="restricted_to[]" size="3" multiple="multiple">
              <?php $selected_any = in_array("0", $groups_id) ?  'selected="selected"' : ''; ?>
              <option value="0" {{ $selected_any }}>{{ Lang::line('pages::lang.-- Any --')->get(ADM_LANG) }}</option>

              <?php // Guests? ?>
              <?php if(in_array("-1", $groups_id)): ?>
              <option selected="selected" value="-1">{{ Lang::line('navigation::lang.Guests')->get(ADM_LANG) }}</option>
              <?php else: ?>
              <option value="-1">{{ Lang::line('navigation::lang.Guests')->get(ADM_LANG) }}</option>
              <?php endif; ?>

              <?php // Authenticated? ?>
              <?php if(in_array("-2", $groups_id)): ?>
              <option selected="selected" value="-2">{{ Lang::line('navigation::lang.Authenticated')->get(ADM_LANG) }}</option>
              <?php else: ?>
              <option value="-2">{{ Lang::line('navigation::lang.Authenticated')->get(ADM_LANG) }}</option>
              <?php endif; ?>


              @foreach($groups as $group)
              <?php $selected = in_array($group->id, $groups_id) ? 'selected="selected"' : ''; ?>
              <option {{ $selected }} value="{{ $group->id }}">{{ $group->name }}</option>
              @endforeach
            </select>
            <span class="help-inline">{{ $errors->has('restricted_to') ? $errors->first('restricted_to') : '' }}</span>
          </div>
        </div>

        <div class="control-group">
          {{ Form::label('is_home', Lang::line('pages::lang.Is default (home) page?')->get(ADM_LANG), array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::checkbox('is_home', 1, (bool)$page->is_home) }}
            <span class="help-inline">{{ $errors->has('is_home') ? $errors->first('is_home') : '' }}</span>
          </div>
        </div>

        <div class="control-group">
          {{ Form::label('strict_uri', Lang::line('pages::lang.Require an exact uri match?')->get(ADM_LANG), array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::checkbox('strict_uri', 1, (bool)$page->strict_uri) }}
            <span class="help-inline">{{ $errors->has('strict_uri') ? $errors->first('strict_uri') : '' }}</span>
          </div>
        </div>
        
      </div>

    </div>
    
    <div class="form-actions">
      <button type="submit" name="btnAction" value="save" class="btn btn-primary">
          <span>{{ Lang::line('pages::lang.Save Changes')->get(ADM_LANG) }}</span>
      </button>
      <button type="submit" name="btnAction" value="save_exit" class="btn btn-primary">
          <span>{{ Lang::line('pages::lang.Save & Exit')->get(ADM_LANG) }}</span>
      </button>
      <a href="{{ URL::base().'/'.ADM_URI.'/'}}pages" class="btn cancel">{{ Lang::line('pages::lang.Cancel')->get(ADM_LANG) }}</a>
    </div>

  </form>
</div>