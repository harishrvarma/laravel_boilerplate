<?php

use Illuminate\Support\Facades\Route;
use Modules\Cron\Http\Controllers\Api\V1\CronController;

Route::middleware(['auth:api'])->prefix('v1/cron')->group(function () {
    Route::get('/listing', [CronController::class,'listing'])->name('cron.listing')->middleware('scope:api.cron.listing')->defaults('label', 'Cron Listing');
    Route::post('/save',[CronController::class,'save'])->name('cron.save')->middleware('scope:api.cron.save')->defaults('label', 'Edit Cron');
    Route::post('/delete',[CronController::class,'delete'])->name('cron.delete')->middleware('scope:api.cron.delete')->defaults('label', 'Delete Cron');
});
