<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\ScaffoldController;
use Modules\Core\Http\Controllers\CoreController;
use Modules\Admin\Http\Middleware\AdminAuthenticate;
use Modules\Admin\Http\Middleware\AdminPermission;

Route::prefix('admin')->group(function(){

    Route::middleware([AdminAuthenticate::class,AdminPermission::class])->group(function () {
        Route::get('scaffold/add', [ScaffoldController::class, 'add'])->name('admin.scaffold.add')->defaults('label', 'Add Module');
        Route::post('scaffold/save', [ScaffoldController::class, 'save'])->name('admin.scaffold.save')->defaults('label', 'Generate Module');
        Route::post('column/save', [CoreController::class, 'saveColumn'])->name('admin.column.saveColumn')->defaults('label', 'Save Column');
    });
});
