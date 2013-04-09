<ul {{ $class }} >
    @foreach($items as $node)
        <li><a class="" href="{{ URL::base().'/'.ADM_URI.'/'.$node['url'] }}">{{ Str::title($node['title']) }}</a>
            @if( ! empty($node['children']))
                {{ themes\render_admin_sub_menu_items($node['children']) }}
            @endif
        </li>
    @endforeach
</ul>