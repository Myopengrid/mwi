<a class="module-description-info" href="#" data-content="{{ $message }}" data-placement="top" rel="popover" data-original-title="{{ $module_rq->name }}" style="color:{{$class}}">
    @if(isset($module->slug))
    {{ $module->slug }}
    @else
    {{ $module_slug }}
    @endif
</a>