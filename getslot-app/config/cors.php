<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    // Paths where CORS policies should be applied
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'livewire/*'],

    // Allowed HTTP methods (e.g., GET, POST, PUT)
    'allowed_methods' => ['*'],

    // Allowed origins (e.g., domains allowed to make requests)
    'allowed_origins' => ['*'],

    // Patterns for allowed origins (useful for wildcard subdomains)
    'allowed_origins_patterns' => [],

    // Allowed headers (headers that are allowed to be sent in requests)
    'allowed_headers' => ['*'],

    // Whether or not the response to the request can be exposed to the frontend
    'exposed_headers' => [],

    // Indicates how long the results of a preflight request can be cached
    'max_age' => 0,

    // Whether credentials (e.g., cookies or Authorization headers) are allowed
    'supports_credentials' => false,

];