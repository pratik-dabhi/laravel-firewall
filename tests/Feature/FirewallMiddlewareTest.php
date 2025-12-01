<?php

namespace Pratik\Firewall\Tests\Feature;

use Pratik\Firewall\Tests\TestCase;

class FirewallMiddlewareTest extends TestCase
{
    protected function defineRoutes($router)
    {
        $router->get('/', function () {
            return 'ok';
        })->middleware('firewall');
    }

    public function test_blocked_ip_gets_403()
    {
        config([
            'firewall.blacklist' => ['1.2.3.4'],
            'firewall.logging.enabled' => false,
        ]);

        $response = $this->get('/', ['REMOTE_ADDR' => '1.2.3.4']);

        $response->assertStatus(403);
    }

    public function test_allowed_ip_gets_200()
    {
        config([
            'firewall.blacklist' => [],
            'firewall.logging.enabled' => false,
        ]);

        $response = $this->get('/', ['REMOTE_ADDR' => '2.3.4.5']);

        $response->assertStatus(200);
    }
}
