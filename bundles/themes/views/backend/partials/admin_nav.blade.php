@foreach($main_links as $menu_title => $menu_item)
    <?php $menu_title = Str::title(str_replace( '_', ' ', $menu_title)) ?>
    <?php //if this is > 1 means it is a group of items for a menu ?>
    <?php if(count($menu_item) > 1): ?>
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="{{ URL::base().'/'.ADM_URI.'/' }}"> {{ $menu_title }} <b class="caret"></b></a>
        
        {{ themes\render_admin_sub_menu_items($menu_item, 'class="dropdown-menu"') }}
    </li>
    <?php else: ?>
    <?php $menu_url = isset($menu_item['0']['url']) ? $menu_item['0']['url'] : '#' ?>
    <li class="{{ isset($menu_item['0']['li_class']) ? $menu_item['0']['li_class'] : '' }}"><a href="{{ URL::base().'/'.ADM_URI.'/'. $menu_url }}" class="{{ isset($menu_item['0']['class']) ? $menu_item['0']['class'] : '' }}">{{ $menu_title }}</a></li>
    <?php endif ?>
@endforeach        
   