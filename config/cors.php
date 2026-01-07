<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],        // أي method
    'allowed_origins' => ['*'],        // أي origin
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],        // أي header
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,   // خليها false عشان '*' يشتغل
];
