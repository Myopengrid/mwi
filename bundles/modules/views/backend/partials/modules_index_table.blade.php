    @if(isset($modules) and !empty($modules))
    <?php $installed_modules = Config::get('installed_modules'); ?>
    @foreach($modules as $module)
    <tr id="{{$module->slug}}">
        
        <?php $bundle = \Laravel\Bundle::get($module->slug); ?>

        <?php $handles = isset($bundle['handles']) ? 1 : 0; ?>
        @if( isset($installed_modules[$module->slug]) and $module->enabled == true and $handles == 1)
        <td class="collapse"><a href="{{ URL::base().'/'.ADM_URI.'/'.$module->slug }}">{{ $module->name }}</a></td>
        @else
        <td class="collapse">{{ $module->name }}</td>
        @endif

        <td>{{ $module->description }}<p>{{ $module->requirements_to_string() }}</p></td>
        <td>{{ $module->version }}</td>

        <td class="action-buttons-{{$module->slug}}">
            @if($module->enabled == true and isset($installed_modules[$module->slug]))
            <a data-verb="PUT" data-module="modules" data-title="{{ Lang::line('modules::lang.Are you sure you wanto to disable &#34;:module_name&#34;?', array('module_name' => $module->name))->get(ADM_LANG) }}" class="confirm btn btn-mini" href="{{ URL::base().'/'.ADM_URI.'/' }}modules/disable/{{ $module->slug }}"><i class="icon-minus"></i> {{ Lang::line('modules::lang.disable')->get(ADM_LANG) }}</a>
            @endif

            @if( $module->enabled == false and isset($installed_modules[$module->slug]) )
            <a data-verb="PUT" data-module="modules" data-title="{{ Lang::line('modules::lang.Are you sure you wanto to enable &#34;:module_name&#34;?', array('module_name' => $module->name))->get(ADM_LANG) }}" class="confirm btn btn-mini btn-success" href="{{ URL::base().'/'.ADM_URI.'/' }}modules/enable/{{ $module->slug }}"><i class="icon-plus"></i> {{ Lang::line('modules::lang.enable')->get(ADM_LANG) }}</a>
            <a data-module="modules" data-verb="DELETE" data-title="{{ Lang::line('modules::lang.Are you sure you wanto to uninstall &#34;:module_name&#34;?', array('module_name' => $module->name))->get(ADM_LANG) }}" class="btn btn-mini btn-danger confirm" href="{{ URL::base().'/'.ADM_URI.'/' }}modules/{{ $module->slug }}"><i class="icon-remove icon-white"></i> {{ Lang::line('modules::lang.Uninstall')->get(ADM_LANG) }}</a>
            @endif
            
            @if(!$module->enabled and !isset($installed_modules[$module->slug]) )
            <a data-verb="POST" data-module="modules" data-title="{{ Lang::line('modules::lang.Are you sure you wanto to install &#34;:module_name&#34;?', array('module_name' => $module->name))->get(ADM_LANG) }}" class="confirm btn btn-mini btn-primary" href="{{ URL::base().'/'.ADM_URI.'/' }}modules/{{ $module->slug }}"><i class="icon-ok"></i> {{ Lang::line('modules::lang.Install')->get(ADM_LANG) }}</a>
            <a data-module="modules" data-verb="POST" data-title="{{ Lang::line('modules::lang.Are you sure you wanto to remove &#34;:module_name&#34;?', array('module_name' => $module->name))->get(ADM_LANG) }}" class="btn btn-mini btn-danger confirm" href="{{ URL::base().'/'.ADM_URI.'/' }}modules/remove/{{ $module->slug }}"><i class="icon-trash icon-white"></i> {{ Lang::line('modules::lang.Remove')->get(ADM_LANG) }}</a>
            @endif
        </td>
        <td>OK</td>
    </tr>
    @endforeach
    @endif
