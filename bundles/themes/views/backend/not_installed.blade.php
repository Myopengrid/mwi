<div class="row">
    <div class="span12">
    <p>{{ Lang::line('themes::lang.Here you will find a list of themes that were uploaded but not installed')->get(ADM_LANG) }}</p>

    <table class="table table-bordered">
        @if(isset($not_installed_themes) and !empty($not_installed_themes))
        <thead>
            <tr>
                <th>{{ Lang::line('themes::lang.Name')->get(ADM_LANG) }}</th>
                <th><span>{{ Lang::line('themes::lang.Description')->get(ADM_LANG) }}</span></th>
                <th>{{ Lang::line('themes::lang.Version')->get(ADM_LANG) }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($not_installed_themes as $theme)
            <tr id="theme-{{ $theme['slug'] }}">
                <td>{{ Lang::line('themes::lang.'.$theme['name'])->get(ADM_LANG) }}</td>
                <td>{{ Lang::line('themes::lang.'.$theme['description'])->get(ADM_LANG) }}</td>
                <td>{{ $theme['version'] }}</td>
                <td class="actions">
                    <a data-module="themes" data-verb="POST" data-title="{{ Lang::line('themes::lang.Are you sure you want install this theme?')->get(ADM_LANG)}}" class="btn btn-mini confirm" href="{{ URL::base().'/'.ADM_URI.'/' }}themes/install/{{ $theme['slug'] }}"><i class="icon-arrow-right"></i> {{ Lang::line('themes::lang.Install')->get(ADM_LANG) }}</a>
                    <a data-module="themes" data-verb="DELETE" data-title="{{ Lang::line('themes::lang.Are you sure to delete the theme [:theme_name] ?', array('theme_name' => $theme['name']))->get(ADM_LANG) }}" class="confirm btn btn-mini btn-danger" href="{{ URL::base().'/'.ADM_URI.'/' }}themes/{{ $theme['slug'] }}"><i class="icon-trash icon-white"></i> {{ Lang::line('themes::lang.Delete')->get(ADM_LANG) }}</a>
                </td>
            </tr>
            @endforeach
        @else
        <tbody>
            <tr>
                <td>{{ Lang::line('themes::lang.All themes are installed')->get(ADM_LANG) }}</td>
            </tr>
        @endif
        </tbody>
        </table>
    </div>
</div>

