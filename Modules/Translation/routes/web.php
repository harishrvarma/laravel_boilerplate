<?php

use Illuminate\Support\Facades\Route;
use Modules\Translation\Http\Controllers\TranslationController;
use Modules\Admin\Http\Middleware\AdminAuthenticate;
use Modules\Admin\Http\Middleware\AdminPermission;

Route::prefix('admin')->group(function(){

    Route::middleware([AdminAuthenticate::class,AdminPermission::class])->group(function () {
        Route::match(['GET','POST'], '/translation/listing', [TranslationController::class,'listing'])
        ->name('admin.system.translation.listing')->defaults('label', 'Translation Listing');
        Route::get('/translation/add', [TranslationController::class,'add'])->name('admin.system.translation.add')->defaults('label', 'Add Translation');
        Route::post('/translation/save', [TranslationController::class,'save'])->name('admin.system.translation.save')->defaults('label', 'Save Translation');
        Route::get('/translation/edit/{id}', [TranslationController::class, 'edit'])->name('admin.system.translation.edit')->defaults('label', 'Edit Translation');
        Route::get('/translation/delete/{id}', [TranslationController::class,'delete'])->name('admin.system.translation.delete')->defaults('label', 'Delete Translation');
        Route::post('/translation/massDelete', [TranslationController::class,'massDelete'])->name('admin.system.translation.massDelete')->defaults('label', 'Mass Delete Translation');
        Route::post('/translation/export', [TranslationController::class,'massExport'])->name('admin.system.translation.export')->defaults('label', 'Mass Export Translation');

        Route::get('/translation/addLocale', [TranslationController::class,'addLocale'])->name('admin.system.translation.addLocale')->defaults('label', 'Add Translation Language');
        Route::post('/translation/saveLocale', [TranslationController::class,'saveLocale'])->name('admin.system.translation.saveLocale')->defaults('label', 'Save Translation Language');
    });
});