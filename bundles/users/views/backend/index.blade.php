<?php themes\add_asset('jquery.colorbox.js', 'jquery', array('jquery'), 'footer') ?>
<?php themes\add_asset('filter.js', 'mod: users/js', array('plugins'), 'footer') ?>

<div class="item">
    
    <fieldset id="filters">
        <legend>{{ __('users::lang.Filters')->get(ADM_LANG) }}</legend>
        {{ Form::open(URL::base().'/'.ADM_URI.'/users', 'POST', array('class' => 'form-inline')) }}
            <div style="display:none">
                {{Form::token()}}
            </div>  

            <input type="hidden" name="f_module" value="users" />
            <ul style="float:left">
                <li style="float:left; margin:10px 5px">
                    <select name="f_status">
                        <option value="0" selected="selected">{{ __('users::lang.-- Status All --')->get(ADM_LANG) }}</option>
                        <option value="active">{{ __('users::lang.Active')->get(ADM_LANG) }}</option>
                        <option value="inactive">{{ __('users::lang.Inactive')->get(ADM_LANG) }}</option>
                        <option value="banned">{{ __('users::lang.Banned')->get(ADM_LANG) }}</option>
                    </select>
                </li>
                
                @if(\Bundle::exists('groups'))
                <li style="float:left; margin:10px 5px">
                    <select name="f_group">
                        <option value="0">{{ __('users::lang.-- Groups All --')->get(ADM_LANG) }}</option>
                        @if(isset($groups_dropdown) and !empty($groups_dropdown))
                        <?php foreach($groups_dropdown as $group): ?>
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                        @endif
                    </select>
                </li>
                @endif

                <li style="float:left; margin:10px 5px">
                    <input type="text" name="f_keywords" value=""  />
                </li>
                <li style="float:left; margin:10px 5px">
                    <a href="{{ URL::base().'/'.ADM_URI.'/' }}users" class="btn cancel">{{ __('users::lang.Reset')->get(ADM_LANG) }}</a>
                </li>
            </ul>
        </form>
    </fieldset>
    <form action="{{ URL::base().'/'.ADM_URI.'/' }}users/action" method="post" accept-charset="utf-8">
        <div style="display:none">
            {{Form::token()}}
        </div>

        <div id="filter-stage" style="display: block;"> 
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
                        <td class="align-center">
                            <input type="checkbox" value="{{ $user->id }}" name="action_to[]">
                        <td>
                            <a class="modal-large cboxElement" target="_blank" href="{{ URL::base() }}/admin/users/preview/1">
                            {{ Str::title($user->avatar_first_name) . ' ' . Str::title($user->avatar_last_name) }}</a>
                        </td>
                        <td class="collapse"><a href="mailto:<?php echo $user->useremail ?>">{{ $user->email }}</a></td>
                        @if(\Bundle::exists('groups'))
                        <td>{{ isset($user->group->name) ? $user->group->name : 'Undefined' }}</td>
                        @endif
                        <td class="collapse">{{ Str::title($user->status) }}</td>
                        <td class="collapse">{{ $user->created_at }}</td>
                        <td class="collapse actions">
                            <a class="btn edit btn-mini" href="{{ URL::base() }}/admin/users/{{ $user->id }}/edit"><i class="icon-edit"></i> {{ Lang::line('users::lang.Edit')->get(ADM_LANG) }}</a>
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
        </div>

        <div class="table_action_buttons">
            <button type="submit" name="btnAction" value="activate" class="btn">
                <i class="icon-ok"></i> {{ Lang::line('users::lang.Activate')->get(ADM_LANG) }}
            </button>
            <button title="{{ __('users::lang.Are you really sure to procced and destroy users?')->get(ADM_LANG) }}" type="submit" name="btnAction" value="delete" class="btn btn-danger confirm">
                <i class="icon-trash icon-white"></i> {{ Lang::line('users::lang.Delete')->get(ADM_LANG) }}
            </button>
        </div>
    </form>
</div>