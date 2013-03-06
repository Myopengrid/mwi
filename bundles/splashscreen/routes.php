<?php
// Flash News Controller
Route::get(ADM_URI.'/(:bundle)/flash_news', function()
{
    return Controller::call('splashscreen::backend.flashnews@index');
});

Route::get(ADM_URI.'/(:bundle)/flash_news/new', function()
{
    return Controller::call('splashscreen::backend.flashnews@new');
});

Route::get(ADM_URI.'/(:bundle)/flash_news/(:num)/edit', function($id)
{
    return Controller::call('splashscreen::backend.flashnews@edit', array($id));
});

Route::put(ADM_URI.'/(:bundle)/flash_news/(:num)', function($id)
{
    return Controller::call('splashscreen::backend.flashnews@update', array($id));
});

Route::post(ADM_URI.'/(:bundle)/flash_news', function()
{
    return Controller::call('splashscreen::backend.flashnews@create');
});

Route::delete(ADM_URI.'/(:bundle)/flash_news/(:num)', function($id)
{
    return Controller::call('splashscreen::backend.flashnews@destroy', array($id));
});

// Splash Screen Controller
Route::put(ADM_URI.'/(:bundle)', function()
{
    return Controller::call('splashscreen::backend.splashscreen@update');
});

Route::get(ADM_URI.'/(:bundle)', function()
{
    return Controller::call('splashscreen::backend.splashscreen@index');
});

Route::get(ADM_URI.'/(:bundle)/images_backgrounds', function()
{
    return Controller::call('splashscreen::backend.imagesbackgrounds@index');
});

Route::post(ADM_URI.'/(:bundle)/images_backgrounds', function()
{
    return Controller::call('splashscreen::backend.imagesbackgrounds@create');
});

Route::delete(ADM_URI.'/(:bundle)/images_backgrounds/(:any)/(:any)', function($action, $image_name)
{
    return Controller::call('splashscreen::backend.imagesbackgrounds@destroy', array($action, $image_name));
});

//
// FLASH NEWS FRONTEND ROUTES
//
$flash_news_handler = Config::get('settings::core.splashscreen_flash_news_handler');
Route::get($flash_news_handler.'/(:any?)', function($flash_news_handler = '')
{
    return Controller::call('splashscreen::frontend.flashnews@index', array($flash_news_handler));
});

$splashscreen_handler = Config::get('settings::core.splashscreen_slug');
Route::get($splashscreen_handler, function()
{
    return Controller::call('splashscreen::frontend.splashscreen@index');
});