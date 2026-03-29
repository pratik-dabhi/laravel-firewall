<?php

namespace PratikDabhi\Firewall\Services;

interface GeoIPServiceInterface
{
    /**
     * Get the ISO-3166-1 alpha-2 country code for a given IP address.
     *
     * @param string $ip
     * @return string|null
     */
    public function getCountryCode(string $ip): ?string;
}
