<?php

use Illuminate\Support\Facades\Route;
use Modules\Cache\Http\Controllers\CacheController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('caches', CacheController::class)->names('cache');
});
