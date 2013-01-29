
<?php themes\add_asset('index.css', 'mod: pages/css') ?>

<?php themes\add_asset('jquery.cooki.js', 'jquery', array('jquery'), 'footer') ?>
<?php themes\add_asset('jquery.colorbox.js', 'jquery', array('jquery'), 'footer') ?>
<?php themes\add_asset('jquery.ui.nestedSortable.js', 'jquery', array(), 'footer') ?>
<?php themes\add_asset('jquery.stickyscroll.js', 'jquery', array(), 'footer') ?>
<?php themes\add_asset('index.js', 'mod: pages/js', array('scripts'), 'footer') ?>

<div class="row">
<div class="span6">
    <br />
    
    <div class="">
        <div id="page-list">
            <ul class="sortable ui-sortable">
                {{ \Ioc::resolve('Menu')->make($pages, '', 'pages::partials.menu_li') }}
            </ul>
        </div>
    </div>
</div>

<div class="span6">

    <br />
    
    <div class="" >
        <div id="page-details" >
        <p>
            {{ Lang::line('pages::lang.The list on the left represents the pages of your site You can drag pages to order them; drag horizontally to make it a sub page When you click on the title of the page you will see all kinds of useful information')->get(ADM_LANG) }}
        </p>
        </div>
    </div>
</div>

</div>
