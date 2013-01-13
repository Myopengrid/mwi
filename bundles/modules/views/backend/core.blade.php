<div class="item">
    <p>{{ Lang::line('modules::lang.Below is the list of main modules and their information Main modules are so important that can not be disabled or removed')->get(ADM_LANG) }}</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>{{ Lang::line('modules::lang.Name')->get(ADM_LANG) }}</th>
                <th><span>{{ Lang::line('modules::lang.Description')->get(ADM_LANG) }}</span></th>
                <th>{{ Lang::line('modules::lang.Version')->get(ADM_LANG) }}</th>
                <th>{{ Lang::line('modules::lang.Status')->get(ADM_LANG) }}</th>
                
            </tr>
        </thead>    
        <tbody>
            @if(isset($core_modules) and !empty($core_modules))
            @foreach ($core_modules as $module)
            <tr>
                @if($module->slug == 'admin')
                    <td class="collapse"><a href="{{ URL::base().DS.ADM_URI }}">{{ $module->name }}</a></td>
                @else
                    <?php $bundle = Bundle::get($module->slug); ?>
                    <?php $handles = isset($bundle['handles']) ? 1 : 0; ?>
                    @if($module->installed and $module->enabled and $handles == 1)
                    <td class="collapse"><a href="{{ URL::base().DS.ADM_URI.DS.$module->slug }}">{{ $module->name }}</a></td>
                    @else
                    <td class="collapse">{{ $module->name }}</td>
                    @endif
                @endif

                <td>{{ Lang::line($module->slug.'::lang.'.$module->description)->get(ADM_LANG) }}</td>
                <td class="align-center">{{ $module->version }}</td>
                <td class="align-center">OK</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td></td>
                <td colspan="2">{{ Lang::line('modules::lang.No core modules were found. what?')->get(ADM_LANG) }}</td>
                <td></td>
            </tr>
            @endif
        </tbody>    
    </table>
</div>