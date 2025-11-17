<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\ScaffoldController;
use Modules\Core\Http\Controllers\CoreController;
use Modules\Admin\Http\Middleware\AdminAuthenticate;
use Modules\Admin\Http\Middleware\AdminPermission;
use Modules\Core\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Session;

Route::prefix('admin')->group(function(){
    Route::middleware([AdminAuthenticate::class, AdminPermission::class, SetLocale::class])->group(function () {
        Route::get('scaffold/add', [ScaffoldController::class, 'add'])->name('admin.scaffold.add')->defaults('label', 'Add Module');
        Route::post('scaffold/save', [ScaffoldController::class, 'save'])->name('admin.scaffold.save')->defaults('label', 'Generate Module');
        Route::get('setlocale/{locale}', function ($locale) {
            Session::put('admin.locale', $locale);
            return redirect()->back();
        })->name('admin.setlocale');
        
    });
});