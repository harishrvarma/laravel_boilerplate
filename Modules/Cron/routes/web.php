<?php

use Illuminate\Support\Facades\Route;
use Modules\Cron\Http\Controllers\CronController;
use Modules\Admin\Http\Middleware\AdminAuthenticate;



Route::prefix('admin')->group(function(){
    Route::middleware(AdminAuthenticate::class)->group(function () {
        Route::match(['GET','POST'], '/cron/listing', [CronController::class,'listing'])
        ->name('admin.cron.listing');
        Route::get('/cron/add', [CronController::class,'add'])->name('admin.cron.add');
        Route::post('/cron/save', [CronController::class,'save'])->name('admin.cron.save');
        Route::get('/cron/edit/{id}', [CronController::class, 'edit'])->name('admin.cron.edit');
        Route::put('/cron/update', [CronController::class,'update'])->name('admin.cron.update');
        Route::get('/cron/delete/{id}', [CronController::class,'delete'])->name('admin.cron.delete');
        Route::post('/cron/massDelete', [CronController::class,'massDelete'])->name('admin.cron.massDelete');
    });
});