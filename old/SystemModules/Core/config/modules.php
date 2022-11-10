<?php

return [

    'base_namespace' => 'Modules',

    'config' => [
        'path' => base_path(env('MODULES_CONFIG_PATH', 'modules.json')),
    ],

    'prefix_route_namespace' => true,

    'prefix' => [
        'web' => env('WEB_PREFIX', ''),
        'api' => env('API_PREFIX', 'api'),
    ],

];
