<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\ConfigController;
use Modules\Admin\Http\Middleware\AdminAuthenticate;

Route::prefix('admin')->group(function(){
    
    Route::middleware(AdminAuthenticate::class)->group(function () {

        Route::match(['GET','POST'], '/config/listing', [ConfigController::class,'listing'])
        ->name('admin.config.listing')->defaults('label', 'Config Listing');
        Route::get('/config/add', [ConfigController::class,'add'])->name('admin.config.add')->defaults('label', 'Add Config');
        Route::get('/config/addFields', [ConfigController::class,'addFields'])->name('admin.config.addFields')->defaults('label', 'Add Config Field');
        Route::post('/config/save', [ConfigController::class,'save'])->name('admin.config.save')->defaults('label', 'Save Config Setting');
        Route::post('/config/saveFields', [ConfigController::class,'saveFields'])->name('admin.config.saveFields')->defaults('label', 'Save Config Field');
        Route::post('/config/saveConfig', [ConfigController::class,'saveConfig'])->name('admin.config.saveConfig')->defaults('label', 'Save Config');
        Route::get('/config/edit/{id}', [ConfigController::class,'edit'])->name('admin.config.edit')->defaults('label', 'Edit Config');
    });
});

