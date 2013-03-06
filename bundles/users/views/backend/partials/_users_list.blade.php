@if(!isset($users) or empty($users))
    <table border="0" class="table table-bordered">
        <tbody>
            <tr>
                <td >{{ __('users::lang.No users found')->get(ADM_LANG) }}</td>
            </tr>
        </tbody>
    </table>
@else
{{ $pagination_links }}

<table border="0" class="table table-bordered">

    <thead>
        <tr>
            <th class="align-center" with="30"><input type="checkbox" class="check-all" value="" name="action_to_all"></th>
            <th>{{ __('users::lang.Name')->get(ADM_LANG) }}</th>
            <th class="collapse">{{ __('users::lang.E-Mail')->get(ADM_LANG) }}</th>
            @if(\Bundle::exists('groups'))
            <th>{{ __('users::lang.Group')->get(ADM_LANG) }}</th>
            @endif
            <th class="collapse">{{ __('users::lang.Active')->get(ADM_LANG) }}</th>
            <th class="collapse">{{ __('users::lang.Joined')->get(ADM_LANG) }}</th>
            <th class="collapse" width="200"></th>
        </tr>
    </thead>

    <tbody>
        @foreach($users as $user)
        <tr id="{{ $user->id }}">
            <td class="align-center"><input type="checkbox" value="{{ $user->id }}" name="action_to[]"></td>
            <td>
                <a class="modal-large cboxElement" target="_blank" href="{{ URL::base().'/'.ADM_URI }}/users/preview/1">
                {{ Str::title($user->avatar_first_name) . ' ' . Str::title($user->avatar_last_name) }}</a>
            </td>
            <td class="collapse"><a href="mailto:<?php echo $user->useremail ?>">{{ $user->email }}</a></td>
            @if(\Bundle::exists('groups'))
            <td>{{ isset($user->group->name) ? $user->group->name : 'Undefined' }}</td>
            @endif
            <td class="collapse">{{ __('users::lang.'.$user->status)->get(ADM_LANG) }}</td>
            <td class="collapse">{{ $user->created_at }}</td>
            <td class="collapse actions">
                <a class="btn edit btn-mini" href="{{ URL::base().'/'.ADM_URI }}/users/{{ $user->id }}/edit"><i class="icon-edit"></i> {{ Lang::line('users::lang.Edit')->get(ADM_LANG) }}</a>
                @if( !$user->is_core)
                @if($user->id != Auth::user()->id)
                <a data-module="users" data-verb="DELETE" data-title="{{ __('users::lang.Are you sure to destroy the user?', array('avatar_name' => Str::title($user->avatar_first_name) . ' ' . Str::title($user->avatar_last_name)))->get(ADM_LANG)}}" class="confirm btn btn-danger btn-mini delete" href="{{ URL::base().'/'.ADM_URI }}/users/{{ $user->id }}"><i class="icon-trash icon-white"></i> {{ Lang::line('users::lang.Delete')->get(ADM_LANG) }}</a>
                @endif
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $pagination_links }}

@endif
