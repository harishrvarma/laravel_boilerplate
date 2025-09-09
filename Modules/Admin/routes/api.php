<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\Api\V1\AdminController;
use Modules\Admin\Http\Controllers\Api\V1\RoleController;
use Modules\Admin\Http\Controllers\Api\V1\ResourceController;

Route::middleware(['auth:api'])->prefix('v1/admin')->group(function () {
    Route::get('/listing', [AdminController::class,'listing'])
        ->name('admin.listing')
        ->middleware('scope:api.admin.listing')
        ->defaults('label', 'Api User Listing');

    Route::post('/save', [AdminController::class,'save'])
        ->name('admin.save')
        ->middleware('scope:api.admin.save')
        ->defaults('label', 'Save Api User');

    Route::post('/delete', [AdminController::class,'delete'])
        ->name('admin.delete')
        ->middleware('scope:api.admin.delete')
        ->defaults('label', 'Delete Api User');
});

Route::middleware(['auth:api'])->prefix('v1/role')->group(function () {
    Route::get('/listing', [RoleController::class,'listing'])
        ->name('role.listing')
        ->defaults('label', 'Api Role Listing');

    Route::post('/save', [RoleController::class,'save'])
        ->name('role.save')
        ->middleware('scope:api.role.save')
        ->defaults('label', 'Save Api Role');

    Route::post('/delete', [RoleController::class,'delete'])
        ->name('role.delete')
        ->middleware('scope:api.role.delete')
        ->defaults('label', 'Delete Api Role');
});

Route::middleware(['auth:api'])->prefix('v1/resource')->group(function () {
    Route::get('/listing', [ResourceController::class,'listing'])
        ->name('resource.listing')
        ->middleware('scope:api.resource.listing')
        ->defaults('label', 'Api Resource Listing');
});
