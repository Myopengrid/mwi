<?php themes\add_asset('codemirror.css', 'codemirror', array(), 'page') ?>
<?php themes\add_asset('codemirror.js', 'codemirror', array('scripts'), 'before_footer') ?>

<?php themes\add_asset('xml.js', 'codemirror/mode/xml', array('codemirror'), 'before_footer') ?>
<?php themes\add_asset('javascript.js', 'codemirror/mode/javascript', array('codemirror'), 'before_footer') ?>
<?php themes\add_asset('css.js', 'codemirror/mode/css', array('codemirror'), 'before_footer') ?>
<?php themes\add_asset('markdown.js', 'codemirror/mode/markdown', array('codemirror'), 'before_footer') ?>
<?php themes\add_asset('htmlmixed.js', 'codemirror/mode/htmlmixed', array('codemirror'), 'before_footer') ?>

<div class="row">
    <div class="span12">
        {{ Form::open(URL::base().'/'.ADM_URI.'/themes/'.$theme_view->id, 'PUT') }}
            <div style="display:none">
            {{ Form::hidden('theme_id', $theme_view->id ) }}
            {{ Form::hidden('theme_slug', $theme_view->slug ) }}
            {{ Form::token() }}
            </div>
            <div>
                <div  id="page-layout-html">
                    <textarea name="layout_content" class="html_editor" id="html_editor"><?php echo $theme_view->layout_content ?></textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" name="restore" title="{{ Lang::line('themes::module.Are you sure to restore this layout to the original? All your changes will be lost!')->get(ADM_LANG) }}" value="restore" class="confirm btn blue">
                    <span>{{ Lang::line('themes::lang.I Messed Up!')->get(ADM_LANG) }}</span>
                </button>                                  
                <a href="{{ URL::base() .'/'.ADM_URI.'/'}}themes" class="btn gray cancel">{{ Lang::line('themes::lang.Cancel')->get(ADM_LANG) }}</a>
                <button type="submit" name="save" value="save" class="btn btn-primary">
                    <span>{{ Lang::line('themes::lang.Save')->get(ADM_LANG) }}</span>
                </button>
            </div>
        {{ Form::close() }}
    </div>
</div>           