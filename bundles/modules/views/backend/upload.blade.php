<div style="margin-top:30px;" class="row">
    <div class="span12">
        {{ Form::open_for_files(URL::base() .'/'.ADM_URI.'/modules/upload', 'POST', array('class' => 'form-horizontal')) }}
            
            <div style="display:none">
            {{ Form::token() }}
            </div>

            <div class="control-group {{ $errors->has('zip_file') ? 'error' : '' }}">
              <label for="zip_file" class="control-label">{{ Lang::line('modules::lang.Please select a file and click upload')->get(ADM_LANG) }}</label>
              <div class="controls">
                <input name="zip_file" class="input" type="file">
                <p><small>Max file size: {{ ini_get('post_max_size') }}</small></p>
              </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" name="btnAction" value="upload" class="btn btn-primary">
                    <span>{{ __('modules::lang.Upload')->get(ADM_LANG) }}</span>
                </button>
            </div>
        </form>
    </div>
</div>
            