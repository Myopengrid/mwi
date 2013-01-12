<div class="row">
    <div class="span12">
        {{ Form::open(URL::base().'/'.ADM_URI.'/themes/set_default', 'PUT') }}
        <div style="display:none">
            {{ Form::token() }}
            {{ Form::hidden('layout_type', 'frontend') }}
            {{ Form::hidden('method', 'index') }}
        </div>

        <table class="table table-bordered">
            <?php if(isset($themes) and !empty($themes)): ?>
            <thead>
                <tr>
                    <th>{{ Lang::line('themes::lang.Default')->get(ADM_LANG) }}</th>
                    <th>{{ Lang::line('themes::lang.Name')->get(ADM_LANG) }}</th>
                    <th>{{ Lang::line('themes::lang.Description')->get(ADM_LANG) }}</th>
                    <th>{{ Lang::line('themes::lang.Version')->get(ADM_LANG) }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($themes as $theme)
                <tr id="theme-{{ $theme->slug }}">
                    <td>
                        <input <?php echo  $theme->is_default == 1 ? 'checked = "checked"' : '' ?> name="theme" value="{{ $theme->slug }}" type="radio">
                        {{ Form::hidden('layout', $theme->layout) }}
                    </td>
                    <td>{{ $theme->name }}</td>
                    <td>{{ $theme->description }}</td>
                    <td>{{ $theme->version }}</td>
                    <td>
                        <?php 
                            $options = json_decode($theme->options, true);
                            if(is_array($options) and !empty($options)): ?>
                            <a href="{{ URL::base().'/'.ADM_URI.'/'}}themes/options/{{ $theme->id }}" title="{{ $theme->name }}" class="btn options">{{ Lang::line('themes::lang.Options')->get(ADM_LANG) }}</a>
                        <?php endif ?>

                        <a href="{{ URL::base().'/'.ADM_URI.'/'}}themes/{{$theme->id}}/edit" rel="" title="{{ Lang::line('themes::lang.Edit Layout of :theme_name', array('theme_name' => $theme->name))->get(ADM_LANG) }}" class="btn btn-mini"><i class="icon-edit"></i> {{ Lang::line('themes::lang.Edit Layout')->get(ADM_LANG) }}</a>

                        
                        <?php if($theme->is_core == 0 and $theme->is_default == 0): ?>
                        <a data-module="themes" data-verb="DELETE" href="{{ URL::base().'/'.ADM_URI.'/'}}themes/{{ $theme->slug }}" data-title="{{ Lang::line('themes::lang.Are you sure to delete the theme [:theme_name] ?', array('theme_name' => $theme->name))->get(ADM_LANG) }}" class="confirm btn btn-danger btn-mini"><i class="icon-trash icon-white"></i> {{ Lang::line('themes::lang.Delete')->get(ADM_LANG) }}</a>
                        <?php endif ?>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="form-actions">
              <button class="btn btn-primary" name="btnAction" type="submit">
                <span>{{ Lang::line('themes::lang.Set As Default')->get(ADM_LANG) }}</span>
                </button>
            </div>
        
        @else
                <tr>
                    <td>{{ Lang::line('themes::lang.No themes were found')->get(ADM_LANG) }}</td>
                </tr>
            </tbody>
        </table>
        @endif
        {{ Form::close() }}
    </div>
</div>          