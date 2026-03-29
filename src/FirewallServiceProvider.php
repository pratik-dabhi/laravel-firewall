<?php

namespace PratikDabhi\Firewall;

use Illuminate\Support\ServiceProvider;
use PratikDabhi\Firewall\Middleware\Firewall as FirewallMiddleware;
use PratikDabhi\Firewall\Services\FirewallManager;
use PratikDabhi\Firewall\Services\GeoIPService;
use PratikDabhi\Firewall\Services\Rules\BlacklistRule;
use PratikDabhi\Firewall\Services\Rules\CidrRule;
use PratikDabhi\Firewall\Services\Rules\CountryRule;
use PratikDabhi\Firewall\Services\Rules\RateLimitRule;

class FirewallServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/firewall.php', 'firewall');
        
        $this->app->bind(\PratikDabhi\Firewall\Services\GeoIPServiceInterface::class, \PratikDabhi\Firewall\Services\GeoIPService::class);

        $this->app->singleton(FirewallManager::class, function ($app) {
            $config = $app['config']->get('firewall', []);

            $ruleClasses = $config['rules'] ?? [];
            $rules = [];
            
            foreach ($ruleClasses as $ruleClass) {
                if (class_exists($ruleClass)) {
                    $rules[] = $app->make($ruleClass);
                }
            }

            return new FirewallManager($config, $rules);
        });
        
        $this->app->alias(FirewallManager::class, 'firewall');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/firewall.php' => config_path('firewall.php'),
        ], 'firewall-config');

        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'firewall-migrations');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/firewall'),
        ], 'firewall-views');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->app->router->aliasMiddleware('firewall', FirewallMiddleware::class);
        
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'firewall');
        
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }
}
