<div id="page-details">
    <input id="page-id" value="{{$page->id}}" type="hidden">
    <input id="page-uri" value="{{$page->slug}}" type="hidden">

    <fieldset>
        <legend>{{ Lang::line('pages::lang.Details')->get(ADM_LANG)}}</legend>
        <p><strong>ID:</strong> #{{$page->id}}</p>
        <p><strong>{{ Lang::line('pages::lang.Status')->get(ADM_LANG)}}:</strong> {{ Lang::line('pages::lang.'.$page->status)->get(ADM_LANG) }}</p>
        <p>
            <strong>Slug:</strong>
            <a id="page-preview" class="cboxElement" href="{{URL::base().'/'.ADM_URI.'/'}}pages/preview/{{ $page->id }}" rel="modal-large" target="_blank">{{URL::base()}}/page/{{$page->slug}}</a>
        </p>
    </fieldset>

    <!-- Meta data tab -->
    <fieldset>
        <legend>{{ Lang::line('pages::lang.Meta Data')->get(ADM_LANG) }}</legend>
        <p><strong>{{ Lang::line('pages::lang.Meta Title')->get(ADM_LANG) }}:</strong> {{ empty($page->meta_title) ? ' — ' : $page->meta_title }}</p>
        <p><strong>{{ Lang::line('pages::lang.Meta Keywords')->get(ADM_LANG) }}:</strong> {{ empty($page->meta_keywords) ? ' — ' : $page->meta_keywords }}</p>
        <p><strong>{{ Lang::line('pages::lang.Meta Description')->get(ADM_LANG) }}:</strong> {{ empty($page->meta_description) ? ' — ' : $page->meta_description }}</p>
    </fieldset> 

    <fieldset>
        <legend>{{ Lang::line('pages::lang.Actions')->get(ADM_LANG) }}</legend>
        <div class="btn-group">
            <a href="{{URL::base().'/'.ADM_URI.'/'}}pages/{{$page->id}}/edit" type="button" class="btn btn-primary"><i class="icon-edit"></i> {{ Lang::line('pages::lang.Edit')->get(ADM_LANG) }}</a>
            <a href="{{URL::base().'/'.ADM_URI.'/'}}pages/new/{{$page->id}}" type="button" class="btn"><i class="icon-tags"></i> {{ Lang::line('pages::lang.Duplicate')->get(ADM_LANG) }}</a>
            @if(!$page->is_core)  
            <a href="{{ URL::base().'/'.ADM_URI.'/pages/'.$page->id }}" class="btn btn-danger" id="delete-page" type="button" ><i class="icon-trash icon-white"></i> {{ Lang::line('pages::lang.Delete')->get(ADM_LANG) }}</a>
            @endif
        </div>
    </fieldset>
    </div>
</div>
<script>
$('#delete-page').on('click', function(e) {
    e.preventDefault();
    var confirmation = confirm('{{ Lang::line('pages::lang.Are you sure? Delete this page?')->get(ADM_LANG) }}');
    if (confirmation == true) {
        var post_data = { _method: "DELETE", csrf: CSRF_TOKEN};
        $.post("{{ URL::base().'/'.ADM_URI.'/pages/'.$page->id }}", post_data , function(data) {
            var response = jQuery.parseJSON(data);
            if(response.success == 'true') {
                // redirecting to refresh pages list
                // TODO refresh just pages list instead
                // redirect
                window.location.href = response.url; 
            }
            return false;  
        } );
    }
    else {
      return false;
    }
});
</script>
    