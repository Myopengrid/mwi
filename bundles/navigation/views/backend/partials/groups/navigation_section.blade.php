    <div class="row">
    <div class="span12">             
        <div rel="{{$navigation->id}}" class="group-{{$navigation->id}} box">
            <div style="margin-bottom:10px; border-radius: 4px 4px 4px 4px; background-color:#E5E5E5; min-height: 40px;" class="title">
                <ul>
                    <li>
                        <h2 rel="tooltip" style="float:left; max-width:400px;" title="Abbreviation: {{$navigation->slug}}" class="nav-group-bar">{{Str::title($navigation->title)}}</h2>
                        <a style="margin-right:5px; margin-top:5px; float:right;" href="{{ URL::base().'/'.ADM_URI }}/navigation/links/new/{{$navigation->id}}" rel="{{$navigation->id}}" class="add ajax btn btn-primary"><i class="icon-plus"></i> {{ Lang::line('navigation::lang.New Link')->get(ADM_LANG) }}</a>                    </li>
                    @if(!$navigation->is_core)
                    <li>
                        <h4 style="float:left; margin-top:12px;" class="form-title group-title-{{$navigation->id}}"></h4>
                        <a data-module="navigation" data-verb="DELETE" style="margin-right:5px; margin-top:5px; float:right;" data-title="{{ Lang::line('navigation::lang.Are you sure you would like to delete this navigation group? This will delete ALL navigation links within this group')->get(ADM_LANG) }}" href="{{ URL::base().'/'.ADM_URI }}/navigation/groups/{{$navigation->id}}" class="tooltip-e confirm btn btn-danger"><i class="icon-trash icon-white"></i> {{ Lang::line('navigation::lang.Delete')->get(ADM_LANG) }}</a>
                    </li>
                    @endif
                </ul>
            
            </div>
            
                            
                <div class="item collapsed">
                    
                    <div id="link-list">
                        <ul class="sortable ui-sortable">
                            {{ \Ioc::resolve('Menu')->make($navigation->get_links(), '', 'navigation::backend.partials.links.navigation_menu_li') }}
                        </ul>
                    </div>
                    
                    <div id="link-details" class="group-{{$navigation->id}}"></div>
                        
                </div> 
        </div>

    </div>
</div>