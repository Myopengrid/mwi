<script>
jQuery(function($){
    var parents = 'div.tabs fieldset';
    
    $(parents +' ul').sortable({
        handle: 'span.move-handle',
        update: function() {
            $(parents +' ul li').removeClass('even');
            $(parents +' ul li:nth-child(even)').addClass('even');
            order = new Array();
            count = 1;
            $(parents +' li').each(function(){
                order.push( [ this.id, count] );
                count++;
            });
            order = order.join(',');

            $.post(SITE_URL + ADM_URI + 'settings/ajax_update_order', {csrf_token: "{{Session::token()}}", order: order });
        }

    });
});
</script>

<style>
label { width: 23% !important; }
span.move-handle { float: right; margin-right: 6px; width: 18px; height: 18px; cursor: move; background: url({{ Url::base() }}/base/assets/img/icons/drag_handle.gif) no-repeat; }
div.tabs fieldset ol li { cursor: default; }
div.type-checkbox label { text-align: left; font-weight: normal; }
</style>

@if(! isset($this->data['setting_section']) and ! empty($this->data['setting_section']))
<div class="item">
    <ul>
        <li> Settings not found </li>
    </ul>
</div>
@else
<div class="item">
    <form action="{{ Url::base() .'/'.ADM_URI }}/users/settings" class="" method="post" accept-charset="utf-8">

        <div style="display:none">
            {{ Form::token() }}
        </div>
        
        <div class="draggable-field">
            <ul class="ui-sortable">       
                @foreach($this->data['setting_section'] as $module => $setting)
                @if(isset($setting->type) and !empty($setting->type))
                <li id="<?php echo $setting->slug ?>" class="even">
                    <div class="control-group ">
                      <label for="{{ $setting->slug }}" class="control-label">{{ $setting->title }}</label>
                      <div class="controls">
                        <?php echo call_user_func("Fields\Form::macro_$setting->type", $setting->slug, $setting->value, $setting) ?>
                        <span class="help-inline"></span>
                        <span class="move-handle"></span>
                      </div>
                    </div> 
                </li>
                @endif
                @endforeach
            </ul>
        </div>

        <div class="buttons padding-top">
            <button type="submit" name="btnAction" value="save" class="btn blue">
                <span>Save</span>
            </button>
        </div>
    </form>
</div>
@endif   

<script>
jQuery(function($){
    var parents = 'div.draggable-field';
    
    $(parents +' ul').sortable({
        handle: 'span.move-handle',
        update: function() {
            $(parents +' ul li').removeClass('even');
            $(parents +' ul li:nth-child(even)').addClass('even');
            order = new Array();
            $(parents +' li').each(function(){
                order.push( this.id );
            });
            order = order.join(',');

            $.post(SITE_URL + ADM_URI + 'settings/ajax_update_order', { order: order });
        }

    });
});
</script>     