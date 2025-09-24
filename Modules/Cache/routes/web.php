<?php

use Illuminate\Support\Facades\Route;
use Modules\Cache\Http\Controllers\CacheController;
use Modules\Admin\Http\Middleware\AdminAuthenticate;
use Modules\Admin\Http\Middleware\AdminPermission;

Route::prefix('admin')->group(function () {

    Route::middleware([AdminAuthenticate::class, AdminPermission::class])->group(function () {

        // Cache Listing
        Route::match(['GET', 'POST'], '/cache/listing', [CacheController::class, 'listing'])
            ->name('admin.cache.listing')
            ->defaults('label', 'Cache Listing');

        // Clear single cache entry
        Route::get('/cache/clear/{id}', [CacheController::class, 'clear'])
            ->name('admin.cache.clear')
            ->defaults('label', 'Clear Cache');

        // Clear all cache entries
        Route::get('/cache/clearAll', [CacheController::class, 'clearAll'])
        ->name('admin.cache.clearAll')
        ->defaults('label', 'Clear All Cache');

    });
});
