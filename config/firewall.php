<?php

return [

    'whitelist' => [
        // '127.0.0.1',
    ],

    'blacklist' => [
        // '123.45.67.89',
    ],

    'cidr' => [
        // '192.168.0.0/24',
    ],

    'block_countries' => [
        // 'CN', 'RU',
    ],

    'rate_limit' => [
        'max_attempts'  => 0,   // 0 = disabled
        'decay_seconds' => 60,
    ],

    'logging' => [
        'enabled' => true,
        'table'   => 'firewall_logs',
    ],

    'response' => [
        'status'  => 403,
        'message' => 'Access denied by firewall.',
        'type'    => 'auto', // auto|json|abort
    ],
];
