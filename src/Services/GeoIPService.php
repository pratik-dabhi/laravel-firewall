<?php

namespace Pratik\Firewall\Services;

class GeoIPService
{
    public function getCountryCode(string $ip): ?string
    {
        // TODO: integrate real GeoIP (MaxMind, IP2Location, etc.)
        // For now, always return null (no country)
        return null;
    }
}
