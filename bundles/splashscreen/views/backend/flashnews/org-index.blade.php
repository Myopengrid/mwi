<?php themes\add_asset('news.js', 'mod: splashscreen/js', array('scripts'), 'footer') ?>
@if(isset($news) and !empty($news))
<div class="offset4">{{ $pagination->links() }}</div>
{{ Form::open( URL::base().'/'.ADM_URI.'/splashscreen/flash_news/0', 'DELETE') }}
    <div style="display:none">
        {{Form::token()}}
    </div>   
    <div class="item">
        <div>
            <input type="text" value="" name="f_keywords">
            <a class="btn cancel" href="http://mwi.dev/admin/users">Reset</a>
        </div>
        <table class="table table-bordered" border="0">
            <thead>
                <tr>
                    <th width="2%"><input name="action_to_all" value="" class="check-all" type="checkbox"></th>
                    <th>{{ __('splashscreen::flashnews.Title')->get(ADM_LANG) }}</th>
                    <th width="8%">{{ __('splashscreen::flashnews.Status')->get(ADM_LANG) }}</th>
                    <th width="15%">{{ __('splashscreen::flashnews.Date')->get(ADM_LANG) }}</th>
                    <th width="12%"></th>
                </tr>
            </thead>
            <tbody>
            @foreach($news as $the_news)
                <tr id="flash-news-row-{{$the_news->id}}">
                    <td>
                        <input name="action_to[]" value="{{ $the_news->id }}" type="checkbox">
                    </td>
                    <td>{{ Str::title($the_news->title) }}</td>
                    <td>{{ $the_news->is_enabled ? __('splashscreen::flashnews.Published')->get(ADM_LANG) : __('splashscreen::flashnews.Draft')->get(ADM_LANG) }}</td>
                    <td>{{ date(APP_DATE_FORMAT, strtotime($the_news->created_at)) }}</td>
                    <td class="actions">
                        <div class="buttons buttons-small align-center">
                            <a href="{{ URL::base().'/'.ADM_URI }}/splashscreen/flash_news/{{ $the_news->id }}/edit" class="btn btn-mini edit"><i class="icon-edit"></i> {{ __('splashscreen::flashnews.Edit')->get(ADM_LANG) }}</a>
                            <a href="{{ URL::base().'/'.ADM_URI }}/splashscreen/flash_news/{{ $the_news->id }}" data-verb="DELETE" data-title="Are you sure to delete this news?" data-module="splashscreen/flash_news" class="confirm btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> {{ __('splashscreen::flashnews.Delete')->get(ADM_LANG) }}</a>
                        </div>
                    </td>
                </tr>
                @endforeach                                                           
            </tbody>
        </table>

        <div class="table_action_buttons">
            <button title="{{ __('splashscreen::flashnews.Are you sure to destroy all this items?')->get(ADM_LANG)}}" disabled="disabled" type="submit" name="btnAction" value="delete" class="btn btn-danger confirm"><i class="icon-trash icon-white"></i> <span>{{ __('splashscreen::flashnews.Delete')->get(ADM_LANG) }}</span>
            </button>
        </div>
    </div>
{{ Form::close() }}
@else
<div class="item">
    <div style="margin:20px" class="offset5">
        <h3>{{ __('splashscreen::flashnews.No flash news were found')->get(ADM_LANG) }}</h3>
    </div>
</div>
@endif
