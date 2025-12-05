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

    public function test_allowed_ip_passes_through()
    {
        $this->get('/', ['REMOTE_ADDR' => '127.0.0.1'])
            ->assertStatus(200)
            ->assertSee('ok');
    }

    public function test_blocked_ip_gets_403()
    {
        config(['firewall.blacklist' => ['1.2.3.4']]);

        $this->get('/', ['REMOTE_ADDR' => '1.2.3.4'])->assertStatus(403);
    }

    public function test_rate_limited_ip_gets_403_after_threshold()
    {
        config(['firewall.rate_limit.max_attempts' => 3]);
        config(['firewall.rate_limit.decay_seconds' => 60]);
        
        for ($i = 0; $i < 3; $i++) {
            $this->get('/', ['REMOTE_ADDR' => '10.0.0.1'])->assertStatus(200);
        }

        $this->get('/', ['REMOTE_ADDR' => '10.0.0.1'])->assertStatus(403);
    }
}
