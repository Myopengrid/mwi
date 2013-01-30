<?php themes\add_asset('permissions.js', 'mod: permissions/js', array('plugins'), 'footer') ?>
<div class="row">
    <div class="span12">
    {{Form::open(ADM_URI.'/permissions/'. $group_id, 'PUT', array('class' => 'form-horizontal', 'id' => 'edit-permissions'));}}
    <div style="display:none">
        {{ Form::hidden('group_id', $group_id ) }}
        {{Form::token()}}
    </div>
    <table class="table table-condensed">
        <thead>
            <tr>
                <th><input name="action_to_all" value="" id="check-all" class="check-all" title="{{__('permissions::lang.Check to grant FULL permissions to ALL MODULES for this group')->get(ADM_LANG)}}" type="checkbox">
                </th>
                <th>{{__('permissions::lang.Module')->get(ADM_LANG)}}</th>
                <th>{{__('permissions::lang.Roles')->get(ADM_LANG)}}</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($permission_groups) and !empty($permission_groups))
                @foreach($permission_groups as $module_id => $permission)
            <tr>
                <td style="width: 30px">
                    <input name="modules[{{ $module_id }}]" value="{{ $module_id }}" id="{{ $module_id }}" class="select-row" title="{{__('permissions::lang.Check to give full access permission to the &quot;:group_title&quot; module for this group.', array('group_title' => $permission['name']))->get(ADM_LANG)}}" type="checkbox">
                </td>
                <td><label class="inline" for="">{{ $permission['name'] }}</label></td>
                <td>
                    @if(isset($permission['roles']) and !empty($permission['roles']))
                        @foreach($permission['roles'] as $role)
                    
                    <label class="inline">
                           <input name="module_roles[{{ $module_id }}][{{ $role['slug'] }}]" {{ $role['checked'] }} value="{{ $role['value'] }}" class="select-rule" type="checkbox">
                    {{ $role['name'] }}
                    </label>
                    
                        @endforeach
                    @else
                    {{__('permissions::lang.This module provide no roles to manage')->get(ADM_LANG)}}
                    @endif
                </td>
            </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <div class="form-actions">
        <button type="submit" name="btnAction" value="save" class="btn btn-primary">
            <span>{{__('permissions::lang.Save')->get(ADM_LANG)}}</span>
        </button>

        <button type="submit" name="btnAction" value="save_exit" class="btn btn-primary">
            <span>{{__('permissions::lang.Save &amp; Exit')->get(ADM_LANG)}}</span>
        </button>

        <a href="{{URL::base().'/'.ADM_URI}}/permissions" class="btn">{{__('permissions::lang.Cancel')->get(ADM_LANG)}}</a>  
    </div>
{{ Form::close() }}
</div>
</div>           