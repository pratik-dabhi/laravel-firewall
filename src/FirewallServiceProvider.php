<?php

namespace Pratik\Firewall;

use Illuminate\Support\ServiceProvider;
use Pratik\Firewall\Middleware\Firewall as FirewallMiddleware;
use Pratik\Firewall\Services\FirewallManager;
use Pratik\Firewall\Services\GeoIPService;
use Pratik\Firewall\Services\Rules\BlacklistRule;
use Pratik\Firewall\Services\Rules\CidrRule;
use Pratik\Firewall\Services\Rules\RateLimitRule;

class FirewallServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/firewall.php', 'firewall');

        $this->app->singleton(GeoIPService::class, function () {
            return new GeoIPService();
        });

        $this->app->singleton(FirewallManager::class, function ($app) {
            $config = $app['config']->get('firewall', []);

            $rules = [
                new CidrRule(),
                new BlacklistRule(),
                new RateLimitRule(),
            ];

            return new FirewallManager($config, $rules);
        });

        $this->app->alias(FirewallManager::class, 'firewall');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/firewall.php' => config_path('firewall.php'),
        ], 'firewall-config');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->app->router->aliasMiddleware('firewall', FirewallMiddleware::class);
    }
}
