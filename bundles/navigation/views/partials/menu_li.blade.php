<?php //Used by theme menu builder ?>
<?php if($child['link_type'] != 'url'): ?>
<?php $url = URL::base().'/'.$url; ?> 
<?php endif ?>
<li id="{{ $child['li_id'].$child['id'] }}">
    <a href="{{ $url }}" {{ empty($child['target']) ? '' : ' target="_blank" ' }} {{ empty($child['class']) ? '' : ' class="'.$child['class'].'" ' }}>{{ Str::title($child['title']) }}</a>
    @if(isset($child['children']) and !empty($child['children']))
    <ul>
        {{ make($child['children']) }}
    </ul>
    @endif
</li>