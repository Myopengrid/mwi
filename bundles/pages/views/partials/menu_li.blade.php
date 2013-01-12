<li id="{{ $child['li_id'].$child['id'] }}">
    <div class="section-link">
        <a class="{{ $child['class'] }}" {{ empty($child['target']) ? '' : 'target="_blank"' }} href="{{ $url }}" alt="{{ $child['id'] }}" rel="{{ $child['rel'] }}">{{ Str::title($child['title']) }}</a>
    </div>
    @if(isset($child['children']) and !empty($child['children']))
    <ul>
        {{ \Ioc::resolve('Menu')->make($child['children'], '', 'pages::partials.menu_li') }}
    </ul>
    @endif
</li>
