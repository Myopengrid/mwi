<style>
.bs-docs-example-images ul li a img {
    display: inline-block;
    margin: 10px;
}

.bs-docs-example:after {
    background-color: #F5F5F5;
    border: 1px solid #DDDDDD;
    border-radius: 4px 0 4px 0;
    color: #9DA0A4;
    content: "Quick Links";
    font-size: 12px;
    font-weight: bold;
    left: -1px;
    padding: 3px 7px;
    position: absolute;
    top: -1px;
}
.bs-docs-example {
    background-color: #FFFFFF;
    border: 1px solid #DDDDDD;
    border-radius: 4px 4px 4px 4px;
    margin: 15px 0;
    padding: 39px 19px 14px;
    position: relative;
}

</style>

<div class="row">

    <div class="span6">
        <div class="bs-docs-example bs-docs-example-images">
            @if(Bundle::exists('blog'))
            <a href="{{ URL::base().DS.ADM_URI.DS}}blog/comments" class="tooltip-s" original-title="{{ __('admin::lang.Manage Comments')->get(ADM_LANG) }}">
                <img src="http://placehold.it/140x140" alt="{{ __('admin::lang.Manage Comments')->get(ADM_LANG) }}">
            </a>
            @endif

            @if(Bundle::exists('pages'))
            <a href="{{ URL::base().DS.ADM_URI.DS}}pages" class="tooltip-s" original-title="{{ __('admin::lang.Manage Pages')->get(ADM_LANG) }}">
                <img src="http://placehold.it/140x140" alt="{{ __('admin::lang.Manage Pages')->get(ADM_LANG) }}">
            </a>
            @endif

            @if(Bundle::exists('media'))
            <a href="{{ URL::base().DS.ADM_URI.DS}}media" class="tooltip-s" original-title="{{ __('admin::lang.manage_files')->get(ADM_LANG) }}">
                <img src="http://placehold.it/140x140" alt="{{ __('admin::lang.Manage Files')->get(ADM_LANG) }}">
            </a>
            @endif

            @if(Bundle::exists('users'))
            <a href="{{ URL::base().DS.ADM_URI.DS}}users" class="tooltip-s" original-title="{{ __('admin::lang.manage_users')->get(ADM_LANG) }}">
                <img src="http://placehold.it/140x140" alt="{{ __('admin::lang.Manage Users')->get(ADM_LANG) }}">
            </a>
            @endif

            @if(Bundle::exists('email'))
            <a href="{{ URL::base().DS.ADM_URI.DS}}email/send_email" class="tooltip-s" original-title="{{ __('email::lang.Send Email')->get(ADM_LANG) }}">
                <img src="http://placehold.it/140x140" alt="{{ __('admin::lang.Send Email')->get(ADM_LANG) }}">
            </a>
            @endif
          </div>
    </div>

    <div class="span6">
        <div class="bs-docs-example bs-docs-example-images">
            <img class="img-rounded" src="http://placehold.it/140x140">
            <img class="img-circle" src="http://placehold.it/140x140">
            <img class="img-polaroid" src="http://placehold.it/140x140">
          </div>
    </div>

    <div class="span12">
        <div class="bs-docs-example bs-docs-example-images">
            <img class="img-rounded" src="http://placehold.it/140x140">
            <img class="img-circle" src="http://placehold.it/140x140">
            <img class="img-polaroid" src="http://placehold.it/140x140">
          </div>
    </div>


</div>