<?php

return [

    'base_namespace' => 'Modules',

    'config' => [
        'path' => base_path(env('MODULES_CONFIG_PATH', 'modules.json')),
    ],

];
