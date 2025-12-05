<?php

namespace Pratik\Firewall\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Pratik\Firewall\FirewallServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getBasePath()
    {
        return realpath(__DIR__ . '/..');
    }

    protected function getPackageProviders($app)
    {
        return [
            FirewallServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('firewall', [
            'enabled'  => true,
            'blacklist' => [],
            'whitelist' => ['127.0.0.1'],
            'cidr'      => [],
            'block_countries' => [],
            'rate_limit' => [
                'enabled'       => true,
                'max_attempts'  => 10,
                'decay_seconds' => 60,
            ],
            'logging' => [
                'enabled' => true,
                'table'   => 'firewall_logs',
            ],
            'response' => [
                'status'  => 403,
                'message' => 'Access denied by firewall.',
                'type'    => 'auto',
            ],
        ]);
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
