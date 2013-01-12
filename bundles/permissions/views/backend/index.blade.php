
<div class="row">
    <div class="span12">
    <p>{{ __('permissions::lang.You can create custom permissions for different users by assigning them to groups Then you can Edit Permissions for each group and control what modules and "roles" a group can have')->get(ADM_LANG) }}</p>
    <table class="table table-bordered" border="0">
        <thead>
            <tr>
                <th>{{ __('permissions::lang.Group')->get(ADM_LANG) }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if(isset($groups) and !empty($groups))
            @foreach ($groups as $group)
            <tr>
                <td><?php echo $group->name ?></td>
                @if($group->slug != 'admin')
                <td class="buttons actions">
                    <a href="{{URL::base().'/'.ADM_URI.'/'}}permissions/{{$group->id}}/edit" class="btn btn-mini"><i class="icon-wrench"></i> {{ __('permissions::lang.Set Permissions')->get(ADM_LANG) }}</a>
                </td>
                @else
                <td class="buttons actions">
                    {{ __('permissions::lang.The Admin group has access to everything')->get(ADM_LANG) }}
                </td>
                </tr>
                @endif

            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
    </div>
</div>          