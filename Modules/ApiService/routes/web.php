<?php

use Illuminate\Support\Facades\Route;
use Modules\ApiService\Http\Controllers\ApiUserController;
use Modules\Admin\Http\Middleware\AdminAuthenticate;
use Modules\ApiService\Http\Controllers\ApiResourceController;
use Modules\ApiService\Http\Controllers\ApiRoleController;

Route::prefix('admin')->group(function(){
    
    Route::middleware(AdminAuthenticate::class)->group(function () {
        Route::match(['GET','POST'], '/apiuser/listing', [ApiUserController::class,'listing'])
        ->name('admin.apiuser.listing');
        Route::get('/apiuser/add', [ApiUserController::class,'add'])->name('admin.apiuser.add');
        Route::post('/apiuser/save', [ApiUserController::class,'save'])->name('admin.apiuser.save');
        Route::get('/apiuser/edit/{id}', [ApiUserController::class,'edit'])->name('admin.apiuser.edit');
        Route::get('/apiuser/delete/{id}', [ApiUserController::class,'delete'])->name('admin.apiuser.delete');
        Route::post('/apiuser/massDelete', [ApiUserController::class,'massDelete'])->name('admin.apiuser.massDelete');


        Route::match(['GET','POST'], '/apirole/listing', [ApiRoleController::class,'listing'])
        ->name('admin.apirole.listing');
        Route::get('/apirole/add', [ApiRoleController::class,'add'])->name('admin.apirole.add');
        Route::post('/apirole/save', [ApiRoleController::class,'save'])->name('admin.apirole.save');
        Route::get('/apirole/edit/{id}', [ApiRoleController::class,'edit'])->name('admin.apirole.edit');
        Route::get('/apirole/delete/{id}', [ApiRoleController::class,'delete'])->name('admin.apirole.delete');
        Route::post('/apirole/massDelete', [ApiRoleController::class,'massDelete'])->name('admin.apirole.massDelete');


        Route::match(['GET','POST'], '/apiresource/listing', [ApiResourceController::class,'listing'])
        ->name('admin.apiresource.listing');
        Route::get('/apiresource/add', [ApiResourceController::class,'add'])->name('admin.apiresource.add');
        Route::post('/apiresource/save', [ApiResourceController::class,'save'])->name('admin.apiresource.save');
        Route::get('/apiresource/edit/{id}', [ApiResourceController::class,'edit'])->name('admin.apiresource.edit');
        Route::get('/apiresource/delete/{id}', [ApiResourceController::class,'delete'])->name('admin.apiresource.delete');
        Route::post('/apiresource/massDelete', [ApiResourceController::class,'massDelete'])->name('admin.apiresource.massDelete');
    });

});
