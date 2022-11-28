<?php

return [

    'base_namespace' => 'Modules',
    'base_path' => base_path('modules'),

    'config' => [
        'path' => base_path(env('MODULES_CONFIG_PATH', 'modules.json')),
    ],

    'prefix_route_namespace' => false,

    'prefix' => [
        'web' => env('WEB_PREFIX', ''),
        'api' => env('API_PREFIX', 'api'),
    ],

];
