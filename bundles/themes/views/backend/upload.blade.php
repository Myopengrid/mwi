<div class="row">
    <div class="span12">
        {{ Form::open_for_files(URL::base().'/'.ADM_URI.'/themes/upload', 'POST', array('class' => 'form-horizontal')) }}
            <div style="display:none">
            {{ Form::token() }}
            </div>

            <div class="control-group">
                <label for="zip_file" class="control-label">{{ Lang::line('themes::lang.Please select file')->get(ADM_LANG) }}</label>
                <div class="controls">
                    <input type="file" name="zip_file" id="zip_file">
                    <p>{{ Lang::line('themes::lang.Max file size')->get(ADM_LANG) }}: {{ ini_get('post_max_size') }}</p>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" name="btnAction" value="upload" class="btn btn-primary">
                    <span>{{ Lang::line('themes::lang.Upload')->get(ADM_LANG) }}</span>
                </button>
            </div>
        {{ Form::close() }}
    </div>
</div>
            