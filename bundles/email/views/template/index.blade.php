<?php themes\add_asset('jquery.colorbox.js', 'jquery', array('jquery'), 'footer') ?>
@if(isset($custom_templates) and !empty($custom_templates))
{{ Form::open( URL::base().'/'.ADM_URI.'/email/template/0', 'DELETE') }}
    <div style="display:none">
        {{Form::token()}}
    </div>   
    <div class="item">
        <table class="table table-bordered" border="0">
            <thead>
                <tr>
                    <th><input name="action_to_all" value="" class="check-all" type="checkbox"></th>
                    <th>{{ __('email::lang.Name')->get(ADM_LANG) }}</th>
                    <th>{{ __('email::lang.Description')->get(ADM_LANG) }}</th>
                    <th>{{ __('email::lang.Language')->get(ADM_LANG) }}</th>
                    <th width="350"></th>
                </tr>
            </thead>
            <tbody>
            @foreach($custom_templates as $template)
                <tr>
                    <td>@if( !$template->is_core )<input name="action_to[]" value="{{ $template->id }}" type="checkbox">@endif</td>
                    <td>{{ Str::title($template->name) }}</td>
                    <td class="collapse">{{ $template->description }}</td>
                    <td class="collapse">{{ $template->lang }}</td>
                    <td class="actions">
                        <div class="buttons buttons-small align-center">
                            <!-- <a href="{{ URL::base().'/'.ADM_URI }}/email/preview_template/{{ $template->id }}" class="btn btn-mini preview modal cboxElement">{{ __('email::lang.Preview')->get(ADM_LANG) }}</a> -->
                            <a href="{{ URL::base().'/'.ADM_URI }}/email/template/{{ $template->id }}/edit" class="btn btn-mini edit"><i class="icon-edit"></i> {{ __('email::lang.Edit')->get(ADM_LANG) }}</a>
                            <a href="{{ URL::base().'/'.ADM_URI }}/email/template/new/{{ $template->id }}" class="btn btn-mini clone"><i class="icon-tags"></i> {{ __('email::lang.Copy')->get(ADM_LANG) }}</a>
                        </div>
                    </td>
                </tr>
                @endforeach                                                           
            </tbody>
        </table>

        <div class="table_action_buttons">
            <button title="{{ __('email::lang.Are you sure to destroy this template(s)?')->get(ADM_LANG)}}" disabled="disabled" type="submit" name="btnAction" value="delete" class="btn red confirm">
                <span>{{ __('email::lang.Delete')->get(ADM_LANG) }}</span>
            </button>
        </div>
    </div>
{{ Form::close() }}
@else
<div class="item">
    {{ __('email::lang.No custom templates were found')->get(ADM_LANG) }}
</div>
@endif
