<div style="margin-top:25px;" class="row">
    <div class="span12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="40%">{{ __('groups::lang.Name')->get(ADM_LANG) }}</th>
                    <th>{{ __('groups::lang.Short Name')->get(ADM_LANG) }}</th>
                    <th width="300"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($groups as $group)
                <tr>
                    <td>{{$group->name}}</td>
                    <td>{{$group->slug}}</td>
                    @if($group->slug == 'admin')
                    <td>
                        {{ __('groups::lang.The Admin group has access to everything')->get(ADM_LANG) }}         
                    </td>
                    @else
                    <td>
                        @if($group->slug != 'users')
                        <a class="btn btn-mini" href="{{ URL::to(ADM_URI.'/groups/'.$group->id.'/edit') }}"><i class="icon-edit"></i> {{ __('groups::lang.Edit')->get(ADM_LANG) }}</a>
                        @endif
                        @if($group->slug != 'admin' and \Bundle::exists('permissions'))  
                        <a class="btn btn-mini" href="{{ URL::to(ADM_URI.'/permissions/'.$group->id.'/edit') }}"><i class="icon-wrench"></i> {{ __('groups::lang.Set Permissions')->get(ADM_LANG) }}</a>
                        @endif
                        @if($group->is_core != 1)
                        @if($group->id != Auth::user()->group_id)
                        <a class="confirm btn btn-danger btn-mini" data-module="groups" data-verb="DELETE" data-title="{{ __('groups::lang.Are you sure to destroy this group?')->get(ADM_LANG)}}" href="{{ URL::to(ADM_URI.'/groups/'.$group->id) }}"><i class="icon-trash icon-white"></i> {{ __('groups::lang.Delete')->get(ADM_LANG) }}</a>
                        @endif
                        @endif            
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>          