<?php

use Illuminate\Support\Facades\Route;
use Modules\Api\Http\Controllers\ApiUserController;
use Modules\Admin\Http\Middleware\AdminAuthenticate;
use Modules\Api\Http\Controllers\ApiResourceController;
use Modules\Api\Http\Controllers\ApiRoleController;
use Modules\Admin\Http\Middleware\AdminPermission;

Route::prefix('admin')->group(function(){
    
    Route::middleware([AdminAuthenticate::class,AdminPermission::class])->group(function () {
        Route::match(['GET','POST'], '/apiuser/listing', [ApiUserController::class,'listing'])
        ->name('admin.system.apiuser.listing')->defaults('label', 'Api User Listing');
        Route::get('/apiuser/add', [ApiUserController::class,'add'])->name('admin.system.apiuser.add')->defaults('label', 'Add Api User');
        Route::post('/apiuser/save', [ApiUserController::class,'save'])->name('admin.system.apiuser.save')->defaults('label', 'Save Api User');
        Route::get('/apiuser/edit/{id}', [ApiUserController::class,'edit'])->name('admin.system.apiuser.edit')->defaults('label', 'Edit Api User');
        Route::get('/apiuser/delete/{id}', [ApiUserController::class,'delete'])->name('admin.system.apiuser.delete')->defaults('label', 'Delete Api User');
        Route::post('/apiuser/massDelete', [ApiUserController::class,'massDelete'])->name('admin.system.apiuser.massDelete')->defaults('label', 'Mass Delete Api User');
        Route::post('/apiuser/export', [ApiUserController::class,'massExport'])->name('admin.system.apiuser.export')->defaults('label', 'Mass Export Api User');


        Route::match(['GET','POST'], '/apirole/listing', [ApiRoleController::class,'listing'])
        ->name('admin.system.apirole.listing')->defaults('label', 'Api Role Listing');
        Route::get('/apirole/add', [ApiRoleController::class,'add'])->name('admin.system.apirole.add')->defaults('label', 'Add Api Role');
        Route::post('/apirole/save', [ApiRoleController::class,'save'])->name('admin.system.apirole.save')->defaults('label', 'Save Api Role');
        Route::get('/apirole/edit/{id}', [ApiRoleController::class,'edit'])->name('admin.system.apirole.edit')->defaults('label', 'Edit Api Role');
        Route::get('/apirole/delete/{id}', [ApiRoleController::class,'delete'])->name('admin.system.apirole.delete')->defaults('label', 'Delete Api Role');
        Route::post('/apirole/massDelete', [ApiRoleController::class,'massDelete'])->name('admin.system.apirole.massDelete')->defaults('label', 'Mass Delete Api Role');
        Route::post('/apirole/export', [ApiRoleController::class,'massExport'])->name('admin.system.apirole.export')->defaults('label', 'Mass Export Api Role');

        Route::match(['GET','POST'], '/apiresource/listing', [ApiResourceController::class,'listing'])
        ->name('admin.system.apiresource.listing')->defaults('label', 'Api Resource Listing');
    });

});
