<?php

use Illuminate\Support\Str;

return [

    'driver' => env('SESSION_DRIVER', 'file'),

    // Session lifetime in minutes
    'lifetime' => (int) env('SESSION_LIFETIME', 120), // default 2 hours

    // Expire session when browser closes
    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', true),

    // Encrypt session data
    'encrypt' => env('SESSION_ENCRYPT', true),

    'files' => storage_path('framework/sessions'),

    'connection' => env('SESSION_CONNECTION'),

    'table' => env('SESSION_TABLE', 'sessions'),

    'store' => env('SESSION_STORE'),

    'lottery' => [2, 100],

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug((string) env('APP_NAME', 'laravel')).'-session'
    ),

    'path' => env('SESSION_PATH', '/'),

    'domain' => env('SESSION_DOMAIN'),

    // Use HTTPS-only cookies (must set true in production)
    'secure' => env('SESSION_SECURE_COOKIE', true),

    // Prevent JavaScript access
    'http_only' => env('SESSION_HTTP_ONLY', true),

    // Strict SameSite for CSRF protection
    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    // Partitioned cookies (privacy in modern browsers)
    'partitioned' => env('SESSION_PARTITIONED_COOKIE', true),

];
