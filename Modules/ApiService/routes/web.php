<?php

use Illuminate\Support\Facades\Route;
use Modules\ApiService\Http\Controllers\ApiUserController;
use Modules\Admin\Http\Middleware\AdminAuthenticate;
use Modules\ApiService\Http\Controllers\ApiResourceController;
use Modules\ApiService\Http\Controllers\ApiRoleController;
use Modules\Admin\Http\Middleware\AdminPermission;

Route::prefix('admin')->group(function(){
    
    Route::middleware([AdminAuthenticate::class,AdminPermission::class])->group(function () {
        Route::match(['GET','POST'], '/apiuser/listing', [ApiUserController::class,'listing'])
        ->name('admin.apiuser.listing')->defaults('label', 'Api User Listing');
        Route::get('/apiuser/add', [ApiUserController::class,'add'])->name('admin.apiuser.add')->defaults('label', 'Add Api User');
        Route::post('/apiuser/save', [ApiUserController::class,'save'])->name('admin.apiuser.save')->defaults('label', 'Save Api User');
        Route::get('/apiuser/edit/{id}', [ApiUserController::class,'edit'])->name('admin.apiuser.edit')->defaults('label', 'Edit Api User');
        Route::get('/apiuser/delete/{id}', [ApiUserController::class,'delete'])->name('admin.apiuser.delete')->defaults('label', 'Delete Api User');
        Route::post('/apiuser/massDelete', [ApiUserController::class,'massDelete'])->name('admin.apiuser.massDelete')->defaults('label', 'Mass Delete Api User');


        Route::match(['GET','POST'], '/apirole/listing', [ApiRoleController::class,'listing'])
        ->name('admin.apirole.listing')->defaults('label', 'Api Role Listing');
        Route::get('/apirole/add', [ApiRoleController::class,'add'])->name('admin.apirole.add')->defaults('label', 'Add Api Role');
        Route::post('/apirole/save', [ApiRoleController::class,'save'])->name('admin.apirole.save')->defaults('label', 'Save Api Role');
        Route::get('/apirole/edit/{id}', [ApiRoleController::class,'edit'])->name('admin.apirole.edit')->defaults('label', 'Edit Api Role');
        Route::get('/apirole/delete/{id}', [ApiRoleController::class,'delete'])->name('admin.apirole.delete')->defaults('label', 'Delete Api Role');
        Route::post('/apirole/massDelete', [ApiRoleController::class,'massDelete'])->name('admin.apirole.massDelete')->defaults('label', 'Mass Delete Api Role');


        Route::match(['GET','POST'], '/apiresource/listing', [ApiResourceController::class,'listing'])
        ->name('admin.apiresource.listing')->defaults('label', 'Api Resource Listing');
    });

});
