<?php

use Illuminate\Support\Facades\Route;
use Pratik\Firewall\Http\Controllers\DashboardController;

$middlewares = config('firewall.dashboard.middleware', ['web']);

Route::middleware($middlewares)->prefix('firewall')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('firewall.dashboard');
        Route::get('/logs', [DashboardController::class, 'logs'])->name('firewall.logs');
});
