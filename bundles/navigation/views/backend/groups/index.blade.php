<?php themes\add_asset('navigation.css', 'mod: navigation/css', array(), 'header') ?>
<?php themes\add_asset('jquery.colorbox.js', 'jquery', array('jquery'), 'footer') ?>
<?php themes\add_asset('jquery.cooki.js', 'jquery', array(), 'footer') ?>
<?php themes\add_asset('jquery.ui.nestedSortable.js', 'jquery', array(), 'footer') ?>
<?php themes\add_asset('navigation.js', 'mod: navigation/js', array('scripts'), 'footer') ?>

@if(isset($navigation_groups) and !empty($navigation_groups))
    
    @foreach ($navigation_groups as $navigation)
    {{ View::make('navigation::backend.partials/groups/navigation_section', get_defined_vars())->with('navigation', $navigation) }}
    @endforeach

@else
    <div class="blank-slate">
        <p>{{ Lang::line('navigation::lang.There are no navigation groups')->get(ADM_LANG) }}</p>
    </div>
@endif