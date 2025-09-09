<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\LoginController;
use Modules\Admin\Http\Controllers\ResourceController;
use Modules\Admin\Http\Controllers\RoleController;
use Modules\Admin\Http\Middleware\AdminAuthenticate;
use Modules\Admin\Http\Middleware\AdminPermission;

Route::prefix('admin')->group(function(){

    Route::middleware([AdminAuthenticate::class,AdminPermission::class])->group(function () {
        Route::match(['GET','POST'], '/admin/listing', [AdminController::class,'listing'])
        ->name('admin.admin.listing')->defaults('label', 'Admin User Listing');
        Route::get('/admin/add', [AdminController::class,'add'])->name('admin.admin.add')->defaults('label', 'Add Admin User');
        Route::post('/admin/save', [AdminController::class,'save'])->name('admin.admin.save')->defaults('label', 'Save Admin User');
        Route::get('/admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.admin.edit')->defaults('label', 'Edit Admin User');
        Route::get('/admin/delete/{id}', [AdminController::class,'delete'])->name('admin.admin.delete')->defaults('label', 'Delete Admin User');
        Route::post('/admin/massDelete', [AdminController::class,'massDelete'])->name('admin.admin.massDelete')->defaults('label', 'Mass Delete Admin User');


        Route::match(['GET','POST'], '/role/listing', [RoleController::class,'listing'])
        ->name('admin.role.listing')->defaults('label', 'Admin Role Listing');
        Route::get('/role/add', [RoleController::class,'add'])->name('admin.role.add')->defaults('label', 'Add Admin Role');
        Route::post('/role/save', [RoleController::class,'save'])->name('admin.role.save')->defaults('label', 'Save Admin Role');
        Route::get('/role/edit/{id}', [RoleController::class,'edit'])->name('admin.role.edit')->defaults('label', 'Edit Admin Role');
        Route::get('/role/delete/{id}', [RoleController::class,'delete'])->name('admin.role.delete')->defaults('label', 'Delete Admin Role');
        Route::post('/role/massDelete', [RoleController::class,'massDelete'])->name('admin.role.massDelete')->defaults('label', 'Mass Delete Admin Role');


        Route::match(['GET','POST'], '/resource/listing', [ResourceController::class,'listing'])
        ->name('admin.resource.listing')->defaults('label', 'Admin Resource Listing');
    });
    
    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'index'])->name('admin.login');
        Route::post('post', [LoginController::class, 'post'])->name('admin.login.post');
        Route::post('logout', [LoginController::class, 'logout'])->name('admin.logout');
    });
});