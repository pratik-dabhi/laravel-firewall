<?php

use Illuminate\Support\Facades\Route;
use Pratik\Firewall\Http\Controllers\DashboardController;

Route::middleware(['web'])->prefix('firewall')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('firewall.dashboard');
        Route::get('/logs', [DashboardController::class, 'logs'])->name('firewall.logs');
});
