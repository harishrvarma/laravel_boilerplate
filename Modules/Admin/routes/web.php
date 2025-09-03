<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\LoginController;
use Modules\Admin\Http\Controllers\RoleController;
use Modules\Admin\Http\Middleware\AdminAuthenticate;

Route::prefix('admin')->group(function(){
    
    Route::middleware(AdminAuthenticate::class)->group(function () {
        Route::match(['GET','POST'], '/admin/listing', [AdminController::class,'listing'])
        ->name('admin.admin.listing');
        Route::get('/admin/add', [AdminController::class,'add'])->name('admin.admin.add');
        Route::post('/admin/save', [AdminController::class,'save'])->name('admin.admin.save');
        Route::get('/admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.admin.edit');
        Route::put('/admin/update', [AdminController::class,'update'])->name('admin.admin.update');
        Route::get('/admin/delete/{id}', [AdminController::class,'delete'])->name('admin.admin.delete');
        Route::post('/admin/massDelete', [AdminController::class,'massDelete'])->name('admin.admin.massDelete');

        Route::post('logout', [LoginController::class, 'logout'])->name('admin.logout');

        Route::match(['GET','POST'], '/role/listing', [RoleController::class,'listing'])
        ->name('admin.role.listing');
        Route::get('/role/add', [RoleController::class,'add'])->name('admin.role.add');
        Route::post('/role/save', [RoleController::class,'save'])->name('admin.role.save');
        Route::get('/role/edit/{id}', [RoleController::class,'edit'])->name('admin.role.edit');
        Route::put('/role/update', [RoleController::class,'update'])->name('admin.role.update');
        Route::get('/role/delete/{id}', [RoleController::class,'delete'])->name('admin.role.delete');
        Route::post('/role/massDelete', [RoleController::class,'massDelete'])->name('admin.role.massDelete');
    });
    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'index'])->name('admin.login');
        Route::post('post', [LoginController::class, 'post'])->name('admin.login.post');
    });

});

