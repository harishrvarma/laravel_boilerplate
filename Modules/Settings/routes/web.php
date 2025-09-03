<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\ConfigController;
use Modules\Admin\Http\Middleware\AdminAuthenticate;

Route::prefix('admin')->group(function(){
    
    Route::middleware(AdminAuthenticate::class)->group(function () {

        Route::match(['GET','POST'], '/config/listing', [ConfigController::class,'listing'])
        ->name('settings.config.listing');
        Route::get('/config/add', [ConfigController::class,'add'])->name('settings.config.add');
        Route::get('/config/addFields', [ConfigController::class,'addFields'])->name('settings.config.addFields');
        Route::post('/config/save', [ConfigController::class,'save'])->name('settings.config.save');
        Route::post('/config/saveFields', [ConfigController::class,'saveFields'])->name('settings.config.saveFields');
        Route::post('/config/saveConfig', [ConfigController::class,'saveConfig'])->name('settings.config.saveConfig');
        Route::get('/config/edit/{id}', [ConfigController::class,'edit'])->name('settings.config.edit');
        Route::put('/config/update', [ConfigController::class,'update'])->name('settings.config.update');
        Route::get('/config/delete/{id}', [ConfigController::class,'delete'])->name('settings.config.delete');
        Route::post('/config/massDelete', [ConfigController::class,'massDelete'])->name('settings.config.massDelete');
    });
});

