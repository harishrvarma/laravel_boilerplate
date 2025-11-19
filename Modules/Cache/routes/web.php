<?php

use Illuminate\Support\Facades\Route;
use Modules\Cache\Http\Controllers\CacheController;
use Modules\Admin\Http\Middleware\AdminAuthenticate;
use Modules\Admin\Http\Middleware\AdminPermission;

Route::prefix('admin')->group(function () {

    Route::middleware([AdminAuthenticate::class, AdminPermission::class])->group(function () {

        Route::match(['GET', 'POST'], '/cache/listing', [CacheController::class, 'listing'])
            ->name('admin.system.cache.listing')
            ->defaults('label', 'Cache Listing');

        Route::get('/cache/clear/{id}', [CacheController::class, 'clear'])
            ->name('admin.system.cache.clear')
            ->defaults('label', 'Clear Cache');

        Route::get('/cache/clearAll', [CacheController::class, 'clearAll'])
        ->name('admin.system.cache.clearAll')
        ->defaults('label', 'Clear All Cache');

    });
});
