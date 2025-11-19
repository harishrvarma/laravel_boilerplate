<?php

use Illuminate\Support\Facades\Route;
use Modules\Cron\Http\Controllers\CronController;
use Modules\Admin\Http\Middleware\AdminAuthenticate;
use Modules\Admin\Http\Middleware\AdminPermission;

Route::prefix('admin')->group(function(){
    Route::middleware([AdminAuthenticate::class,AdminPermission::class])->group(function () {
        Route::match(['GET','POST'], '/cron/listing', [CronController::class,'listing'])
        ->name('admin.system.cron.listing')->defaults('label', 'Cron Listing');
        Route::get('/cron/add', [CronController::class,'add'])->name('admin.system.cron.add')->defaults('label', 'Add Cron');
        Route::post('/cron/save', [CronController::class,'save'])->name('admin.system.cron.save')->defaults('label', 'Save Cron');
        Route::get('/cron/edit/{id}', [CronController::class, 'edit'])->name('admin.system.cron.edit')->defaults('label', 'Edit Cron');
        Route::get('/cron/delete/{id}', [CronController::class,'delete'])->name('admin.system.cron.delete')->defaults('label', 'Delete Cron');
        Route::post('/cron/massDelete', [CronController::class,'massDelete'])->name('admin.system.cron.massDelete')->defaults('label', 'Mass Delete Cron');
        Route::post('/cron/export', [CronController::class,'massExport'])->name('admin.system.cron.export')->defaults('label', 'Mass Export Cron');
        Route::get('/cron/run/{id}', [CronController::class,'run'])->name('admin.system.cron.run')->defaults('label', 'Run Cron');
    });
});