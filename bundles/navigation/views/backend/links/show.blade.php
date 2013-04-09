
<div id="details-container">
    
    <input id="link-id" type="hidden" value="{{$link->id}}" />
    @if($link->link_type == 'page' and Bundle::exists('pages') and ! is_null($link->page))
    <input id="link-uri" type="hidden" value="{{$link->page->slug}}" />
    @endif

    <fieldset>
        <legend>{{ Lang::line('navigation::lang.Details')->get(ADM_LANG) }}</legend>
        <p>
            <strong>{{ Lang::line('navigation::lang.ID')->get(ADM_LANG) }}:</strong>
            #{{$link->id}}
        </p>

        <p>
            <strong>{{ Lang::line('navigation::lang.Title')->get(ADM_LANG) }}:</strong>
            {{ Str::title($link->title) }}
        </p>

        <p>
            <strong>{{ Lang::line('navigation::lang.Target')->get(ADM_LANG) }}:</strong>
            <?php echo empty($link->target) ? Lang::line('navigation::lang.Current window (default)')->get(ADM_LANG) : $link->target ?>
        </p>
        
        <p>
            <strong>{{ Lang::line('navigation::lang.Class')->get(ADM_LANG) }}:</strong>
            {{$link->class}}
        </p>
        
        <p>
            <strong>{{ Lang::line('navigation::lang.Link Type')->get(ADM_LANG) }}:</strong>
            @if($link->link_type == 'url' or $link->link_type == 'uri')
            {{ Str::upper($link->link_type) }}
            @else
            {{ Str::title($link->link_type) }}
            @endif
        </p>
        
        <p>
            <strong>{{ Lang::line('navigation::lang.Location')->get(ADM_LANG) }}:</strong>
            @if($link->link_type == 'page' and Bundle::exists('pages'))
                @if(is_null($link->page))
                    Page was deleted
                @else
                    <a class="cboxElement" rel="modal-large" target="_blank" href="{{URL::base().'/'.$link->page->slug}}"> {{URL::base().'/'.$link->page->slug}}</a>
                @endif
            @endif

            @if($link->link_type == 'url')
            <a class="cboxElement" rel="modal-large" target="_blank" href="{{$link->url}}"> {{$link->url}}</a>
            @endif
            @if($link->link_type == 'uri')
            <a class="cboxElement" rel="modal-large" target="_blank" href="{{URL::base().'/'.$link->uri}}"> {{URL::base().'/'.$link->uri}}</a>
            @endif
            @if($link->link_type == 'module')
            <a class="cboxElement" rel="modal-large" target="_blank" href="{{URL::base().'/'.$link->module_name}}"> {{URL::base().'/'.$link->module_name}}</a>
            @endif
        </p>
        
        <p>
            <strong>{{ Lang::line('navigation::lang.Restricted To')->get(ADM_LANG) }}:</strong>
            {{ $restricted_to }}
        </p>
    </fieldset> 

        <div class="btn-group" style="margin-left:25px; margin-bottom:25px;">
            <a href="{{URL::base().'/'.ADM_URI}}/navigation/links/{{$link->id}}/edit" rel="{{$link->group_id}}" type="button" class="btn ajax"><i class="icon-pencil"></i> {{ Lang::line('navigation::lang.Edit')->get(ADM_LANG) }}</a>
            <a data-module="navigation" data-verb="DELETE" data-title="{{ Lang::line('navigation::lang.Are you sure to delete this link?')->get(ADM_LANG) }}" href="{{URL::base().'/'.ADM_URI}}/navigation/links/{{$link->id}}" type="button" class="btn btn-danger confirm"><i class="icon-trash icon-white"></i> {{ Lang::line('navigation::lang.Delete')->get(ADM_LANG) }}</a>
        </div>
    
    
</div>