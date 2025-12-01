<?php

namespace Pratik\Firewall\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Pratik\Firewall\FirewallServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [FirewallServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Basic config override for tests if needed
        $app['config']->set('firewall.logging.enabled', false);
    }
}
