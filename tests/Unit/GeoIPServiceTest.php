<?php

namespace PratikDabhi\Firewall\Tests\Unit;

use PratikDabhi\Firewall\Tests\TestCase;
use PratikDabhi\Firewall\Services\GeoIPService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GeoIPServiceTest extends TestCase
{
    protected GeoIPService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GeoIPService();
        Cache::flush();
    }

    public function test_it_returns_null_for_local_ips()
    {
        Http::fake();
        
        $this->assertNull($this->service->getCountryCode('127.0.0.1'));
        $this->assertNull($this->service->getCountryCode('::1'));
        $this->assertNull($this->service->getCountryCode('localhost'));
        
        Http::assertNothingSent();
    }

    public function test_it_fetches_country_code_and_caches_it()
    {
        $ip = '8.8.8.8';
        $country = 'US';

        Http::fake([
            "*" => Http::response(['status' => 'success', 'countryCode' => $country], 200)
        ]);

        // First call should hit HTTP
        $result1 = $this->service->getCountryCode($ip);
        $this->assertEquals($country, $result1);

        // Second call should hit Cache
        $result2 = $this->service->getCountryCode($ip);
        $this->assertEquals($country, $result2);

        Http::assertSentCount(1);
    }

    public function test_it_returns_null_on_api_failure()
    {
        $ip = '8.8.8.8';

        Http::fake([
            "*" => Http::response(['status' => 'fail'], 200)
        ]);

        $result = $this->service->getCountryCode($ip);

        $this->assertNull($result);
    }

    public function test_it_returns_null_and_logs_on_exception()
    {
        $ip = '8.8.4.4';

        Http::fake([
            "*" => function () {
                throw new \Exception('Connection timeout');
            }
        ]);

        Log::shouldReceive('warning')
            ->once()
            ->withArgs(function ($message) use ($ip) {
                return str_contains($message, "Firewall GeoIP lookup failed for IP {$ip}");
            });

        $result = $this->service->getCountryCode($ip);

        $this->assertNull($result);
    }
}
