<?php $image_id = explode('.', $image_name); ?>
<li id="{{ $image_id['0'] }}" class="thumb-image span3">
    <a class="thumbnail" href="#">
        <img alt="260x180" style="width: 260px; height: 180px;" src="{{ URL::base().$path.'?'.time() }}">
    </a>
</li>