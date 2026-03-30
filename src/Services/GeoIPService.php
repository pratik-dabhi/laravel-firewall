<?php

namespace PratikDabhi\Firewall\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GeoIPService implements GeoIPServiceInterface
{
    public function getCountryCode(string $ip): ?string
    {
        // Bypass local IPs
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
            return null;
        }

        $cacheKey = "firewall_geoip_{$ip}";
        $cacheTtl = config('firewall.geoip.cache_ttl', 60 * 60 * 24 * 30); // 30 days default

        return Cache::remember($cacheKey, $cacheTtl, function () use ($ip) {
            try {
                $serviceUrl = config('firewall.geoip.service_url', 'http://ip-api.com/json/{ip}?fields=status,countryCode');
                $url = str_replace('{ip}', $ip, $serviceUrl);
                
                // Keep timeout low to prevent hanging requests in middleware
                $response = Http::timeout(2)->get($url);
                
                if ($response->successful()) {
                    $data = $response->json();
                    if (($data['status'] ?? '') === 'success' && !empty($data['countryCode'])) {
                        return strtoupper($data['countryCode']);
                    }
                }
            } catch (\Exception $e) {
                // Log failure but safely fail-open
                Log::warning("Firewall GeoIP lookup failed for IP {$ip}: " . $e->getMessage());
            }

            return null;
        });
    }
}
