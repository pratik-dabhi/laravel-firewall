<?php

namespace Pratik\Firewall\Services\Rules;

use Pratik\Firewall\Services\GeoIPService;

class CountryRule implements RuleInterface
{
    public function __construct(protected GeoIPService $geo) {}

    public function isBlocked(string $ip, array $config): bool
    {
        $blocked = $config['block_countries'] ?? [];
        if (empty($blocked)) {
            return false;
        }

        $country = $this->geo->getCountryCode($ip);

        return $country && in_array(strtoupper($country), array_map('strtoupper', $blocked), true);
    }

    public function reason(): string
    {
        return 'country_block';
    }
}
