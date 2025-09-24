<?php

use Illuminate\Support\Facades\Route;
use Modules\Translation\Http\Controllers\TranslationController;
use Modules\Admin\Http\Middleware\AdminAuthenticate;
use Modules\Admin\Http\Middleware\AdminPermission;

Route::prefix('admin')->group(function(){

    Route::middleware([AdminAuthenticate::class,AdminPermission::class])->group(function () {
        Route::match(['GET','POST'], '/translation/listing', [TranslationController::class,'listing'])
        ->name('admin.translation.listing')->defaults('label', 'Translation Listing');
        Route::get('/translation/add', [TranslationController::class,'add'])->name('admin.translation.add')->defaults('label', 'Add Translation');
        Route::post('/translation/save', [TranslationController::class,'save'])->name('admin.translation.save')->defaults('label', 'Save Translation');
        Route::get('/translation/edit/{id}', [TranslationController::class, 'edit'])->name('admin.translation.edit')->defaults('label', 'Edit Translation');
        Route::get('/translation/delete/{id}', [TranslationController::class,'delete'])->name('admin.translation.delete')->defaults('label', 'Delete Translation');
        Route::post('/translation/massDelete', [TranslationController::class,'massDelete'])->name('admin.translation.massDelete')->defaults('label', 'Mass Delete Translation');

    });
    
});