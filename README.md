# Laravel Firewall  
<!-- ![GitHub Tests](https://github.com/pratik-dabhi/laravel-firewall/actions/workflows/tests.yml/badge.svg)
![Packagist Version](https://img.shields.io/packagist/v/pratik/laravel-firewall.svg)
![Downloads](https://img.shields.io/packagist/dt/pratik/laravel-firewall.svg)
![License](https://img.shields.io/badge/license-MIT-brightgreen.svg) -->

A powerful, extensible, and developer-friendly **application-level firewall for Laravel 10+**.

This package gives your Laravel app real-time protection through:
- 🔒 **IP Blacklisting / Whitelisting**
- 🌐 **CIDR Blocking**
- 🚫 **Rate Limiting Rule Engine**
- 📄 **Database Logging of Security Events**
- 📊 **Beautiful Firewall Dashboard UI**
- 📜 **Detailed Logs Viewer Page**
- 🧱 **Plug-and-play Middleware**
- ✔ Fully tested using **Laravel Testbench**

It brings enterprise-level request filtering & monitoring into your Laravel application with minimal configuration.

## Installation
```bash
composer require pratik/laravel-firewall
```

## Publishing
### Migrations
```bash
php artisan vendor:publish --tag="firewall-migrations"
php artisan migrate
```

### Config
```bash
php artisan vendor:publish --tag="firewall-config"
```

### Views
```bash
php artisan vendor:publish --tag="firewall-views"
```

## Usage
Register middleware in Laravel 12 (`bootstrap/app.php`):
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'firewall' => \Pratik\Firewall\Middleware\Firewall::class,
    ]);
})
```

Protect routes:
```php
Route::middleware(['firewall'])->group(function () {
    Route::get('/', fn() => 'Protected Page');
});
```

Dashboard routes:
```php
Route::get('/firewall/dashboard', fn() => view('vendor.firewall.dashboard'));
Route::get('/firewall/logs', fn() => view('vendor.firewall.logs'));
```

## Testing
```bash
composer test
```

## Credits
- Pratik Dabhi
- All Contributors

## License
MIT License.