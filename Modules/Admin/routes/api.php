<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminApiController;

Route::middleware(['auth:api'])->prefix('admin')->group(function () {
    Route::get('/listing', [AdminApiController::class,'listing'])->name('api.admin.listing')->defaults('label', 'Api User Listing');
    Route::post('/save',[AdminApiController::class,'save'])->name('api.admin.save')->middleware('can:edit-admin')->defaults('label', 'Edit Api User');
    Route::post('/delete',[AdminApiController::class,'delete'])->name('api.admin.delete')->middleware('can:delete-admin')->defaults('label', 'Delete Api User');
});
