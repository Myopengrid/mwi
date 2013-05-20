<?php

Autoloader::namespaces(array(
    'Pages\Model' => Bundle::path('pages').'models'.DS,
    'Pages'       => Bundle::path('pages').'libraries'.DS,
));

/**
 * Includes out Events file
 */
require_once 'events.php';
