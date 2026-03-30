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
    | Firewall Rules
    |--------------------------------------------------------------------------
    |
    | The classes responsible for evaluating requests. You can add, remove,
    | or reorder rules here to customize your firewall protection.
    |
    */

    'rules' => [
        \PratikDabhi\Firewall\Services\Rules\BlacklistRule::class,
        \PratikDabhi\Firewall\Services\Rules\CidrRule::class,
        \PratikDabhi\Firewall\Services\Rules\CountryRule::class,
        \PratikDabhi\Firewall\Services\Rules\RateLimitRule::class,
    ],

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
    | GeoIP Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the GeoIP service used by CountryRule.
    | We use ip-api.com by default. The results are cached to improve 
    | performance and avoid hitting rate limits.
    |
    */

    'geoip' => [
        'service_url' => env('FIREWALL_GEOIP_URL', 'http://ip-api.com/json/{ip}?fields=status,countryCode'),
        'cache_ttl'   => env('FIREWALL_GEOIP_CACHE_TTL', 60 * 60 * 24 * 30), // Default 30 days
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
        'model'   => \PratikDabhi\Firewall\Models\FirewallLog::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    |
    | Configuration for the built-in firewall dashboard and logs viewer.
    | Route middleware secures public access to the dashboard.
    |
    */

    'dashboard' => [
        'middleware' => ['web'], // Add 'auth' or a custom gate here for protection
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
