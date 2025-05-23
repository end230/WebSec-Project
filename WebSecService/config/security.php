<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Password Security
    |--------------------------------------------------------------------------
    |
    | Define password security requirements across the application
    |
    */
    'password' => [
        'min_length' => 12,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => true,
        'prevent_common' => true,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Define rate limiting for various actions
    |
    */
    'rate_limiting' => [
        'login' => [
            'max_attempts' => 5,
            'decay_minutes' => 1,
        ],
        'register' => [
            'max_attempts' => 3,
            'decay_minutes' => 10,
        ],
        'password_reset' => [
            'max_attempts' => 3,
            'decay_minutes' => 60,
        ],
        'api' => [
            'max_attempts' => 60,
            'decay_minutes' => 1,
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Content Security
    |--------------------------------------------------------------------------
    |
    | Define content security policies
    |
    */
    'content_security' => [
        'allow_iframe' => false,
        'allow_inline_scripts' => false,
        'allowed_domains' => [
            'self',
            'https://cdn.jsdelivr.net',
            'https://fonts.googleapis.com',
            'https://fonts.gstatic.com',
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Input Validation
    |--------------------------------------------------------------------------
    |
    | Define input validation rules
    |
    */
    'input_validation' => [
        'sanitize_html' => true,
        'allow_html_in' => ['description'], // Fields that can contain limited HTML
    ],
];
