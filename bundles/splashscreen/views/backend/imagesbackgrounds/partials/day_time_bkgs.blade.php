<?php $image_id = explode('.', $image_name); ?>
<li id="{{ $image_id['0'] }}" class="thumb-image span3">
    <a class="thumbnail" href="#">
        <img alt="260x180" style="width: 260px; height: 180px;" src="{{ URL::base().$path.'?'.time() }}">
    </a>
    <a href="{{ URL::base().'/'.ADM_URI.'/splashscreen/images_backgrounds/'.$action.'/'.$image_name }}" class="confirm remove-image-btn btn btn-danger" data-action="daytimebkg" data-title="Are you sure to remove this image?" data-verb="DELETE" data-module="splashscreen"><i class="icon icon-white icon-trash"></i> Remove</a>
</li>