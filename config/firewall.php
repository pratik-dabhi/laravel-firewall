<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Global Firewall Toggle
    |--------------------------------------------------------------------------
    |
    | You can turn the firewall on / off from here without removing middleware.
    |
    */

    'enabled' => env('FIREWALL_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Static Rules: IP Blacklist / Whitelist / CIDR
    |--------------------------------------------------------------------------
    */

    // Always blocked IPs (strings, exact match)
    'blacklist' => [
        // '1.2.3.4',
    ],

    // Always allowed IPs (checked before every rule)
    'whitelist' => [
        '127.0.0.1',
    ],

    // CIDR ranges to block
    'cidr' => [
        // '192.168.1.0/24',
    ],

    /*
    |--------------------------------------------------------------------------
    | Country Blocking (requires GeoIP)
    |--------------------------------------------------------------------------
    |
    | You can block specific ISO-3166-1 alpha-2 country codes (e.g. "US", "IN").
    | The GeoIPService is intentionally simple and can be replaced / extended.
    |
    */

    'block_countries' => [
        // 'CN', 'RU',
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Simple IP based rate limit using the cache store. This is not meant to
    | replace Laravel's full rate limiter, but to provide firewall level
    | protection for abusive traffic.
    |
    */

    'rate_limit' => [
        'enabled'       => true,
        'max_attempts'  => 100,  
        'decay_seconds' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Enable logging to the database. Logs are written only for blocked /
    | rate-limited requests to keep the table small.
    |
    */

    'logging' => [
        'enabled' => true,
        'table'   => 'firewall_logs',
    ],

    /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    |
    | Configure how the middleware responds when a request is blocked.
    | type:
    |   - "auto"  => JSON if the request expectsJson(), otherwise HTML
    |   - "json"  => always JSON
    |
    */

    'response' => [
        'status'  => 403,
        'message' => 'Access denied by firewall.',
        'type'    => 'auto',
    ],

];
