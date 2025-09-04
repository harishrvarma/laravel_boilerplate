<?php

use Illuminate\Support\Facades\Route;
use Modules\Cron\Http\Controllers\CronController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('crons', CronController::class)->names('cron');
});
