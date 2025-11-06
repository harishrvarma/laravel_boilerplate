<?php


use Modules\Product\Http\Controllers\Product\Entity\Attribute\ValueController;
use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Middleware\AdminAuthenticate;
use Modules\Admin\Http\Middleware\AdminPermission;
use Modules\Product\Http\Controllers\Product\EntityController;

Route::prefix('admin')->middleware([AdminAuthenticate::class, AdminPermission::class])->group(function () {
    Route::match(['GET','POST'], '/product/entity/listing', [EntityController::class, 'listing'])
        ->name('admin.product.entity.listing')->defaults('label', 'Entity Listing');
    Route::get('/product/entity/add', [EntityController::class, 'add'])
        ->name('admin.product.entity.add')->defaults('label', 'Add Entity');
    Route::post('/product/entity/save', [EntityController::class, 'save'])
        ->name('admin.product.entity.save')->defaults('label', 'Save Entity');
    Route::get('/product/entity/edit/{id}', [EntityController::class, 'edit'])
        ->name('admin.product.entity.edit')->defaults('label', 'Edit Entity');
    Route::get('/product/entity/delete/{id}', [EntityController::class, 'delete'])
        ->name('admin.product.entity.delete')->defaults('label', 'Delete Entity');
    Route::post('/product/entity/massDelete', [EntityController::class, 'massDelete'])
        ->name('admin.product.entity.massDelete')->defaults('label', 'Mass Delete Entity');
    Route::post('/product/entity/export', [EntityController::class, 'massExport'])
        ->name('admin.product.entity.export')->defaults('label', 'Mass Export Entity');
});