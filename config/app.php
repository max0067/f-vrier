<?php

declare(strict_types=1);

return [
    'name' => $_ENV['APP_NAME'] ?? 'SEO Content Generator',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    'timezone' => 'Europe/Paris',
    'locale' => 'fr',

    'providers' => [
        'openai' => [
            'api_key' => $_ENV['OPENAI_API_KEY'] ?? '',
            'model' => $_ENV['OPENAI_MODEL'] ?? 'gpt-4-turbo-preview',
            'max_tokens' => (int)($_ENV['OPENAI_MAX_TOKENS'] ?? 4000),
        ],
        'anthropic' => [
            'api_key' => $_ENV['ANTHROPIC_API_KEY'] ?? '',
            'model' => $_ENV['ANTHROPIC_MODEL'] ?? 'claude-3-opus-20240229',
        ],
        'serp' => [
            'api_key' => $_ENV['SERP_API_KEY'] ?? '',
            'country' => $_ENV['SERP_COUNTRY'] ?? 'fr',
            'language' => $_ENV['SERP_LANGUAGE'] ?? 'fr',
        ],
        'image' => [
            'api_key' => $_ENV['IMAGE_API_KEY'] ?? '',
            'model' => $_ENV['IMAGE_MODEL'] ?? 'dall-e-3',
        ],
    ],

    'cache' => [
        'driver' => $_ENV['CACHE_DRIVER'] ?? 'file',
        'ttl' => (int)($_ENV['CACHE_TTL'] ?? 3600),
        'path' => dirname(__DIR__) . '/storage/cache',
    ],

    'session' => [
        'driver' => $_ENV['SESSION_DRIVER'] ?? 'file',
        'lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 120),
        'path' => dirname(__DIR__) . '/storage/sessions',
    ],

    'logging' => [
        'channel' => $_ENV['LOG_CHANNEL'] ?? 'daily',
        'level' => $_ENV['LOG_LEVEL'] ?? 'error',
        'path' => dirname(__DIR__) . '/storage/logs',
    ],

    'rate_limit' => [
        'requests' => (int)($_ENV['RATE_LIMIT_REQUESTS'] ?? 100),
        'window' => (int)($_ENV['RATE_LIMIT_WINDOW'] ?? 3600),
    ],
];
