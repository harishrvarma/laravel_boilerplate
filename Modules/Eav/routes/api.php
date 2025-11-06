<?php

use Modules\Eav\Http\Controllers\Eav\AttributeController;
use Modules\Eav\Http\Controllers\Eav\EntityController;
use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Middleware\AdminAuthenticate;
use Modules\Admin\Http\Middleware\AdminPermission;

Route::prefix('admin')->middleware([AdminAuthenticate::class, AdminPermission::class])->group(function () {

    Route::match(['GET','POST'], '/attributes/listing', [AttributeController::class, 'listing'])
        ->name('admin.attributes.listing')->defaults('label', 'Attributes Listing');
    Route::get('/attributes/add', [AttributeController::class, 'add'])
        ->name('admin.attributes.add')->defaults('label', 'Add Attributes');
    Route::post('/attributes/save', [AttributeController::class, 'save'])
        ->name('admin.attributes.save')->defaults('label', 'Save Attributes');
    Route::get('/attributes/edit/{id}', [AttributeController::class, 'edit'])
        ->name('admin.attributes.edit')->defaults('label', 'Edit Attributes');
    Route::get('/attributes/delete/{id}', [AttributeController::class, 'delete'])
        ->name('admin.attributes.delete')->defaults('label', 'Delete Attributes');
    Route::post('/attributes/massDelete', [AttributeController::class, 'massDelete'])
        ->name('admin.attributes.massDelete')->defaults('label', 'Mass Delete Attributes');
    Route::post('/attributes/export', [AttributeController::class, 'massExport'])
        ->name('admin.attributes.export')->defaults('label', 'Mass Export Attributes');

    Route::match(['GET','POST'], '/entities/listing', [EntityController::class, 'listing'])
        ->name('admin.entities.listing')->defaults('label', 'Entities Listing');

    Route::get('/entities/add', [EntityController::class, 'add'])
        ->name('admin.entities.add')->defaults('label', 'Add Entity');

    Route::post('/entities/save', [EntityController::class, 'save'])
        ->name('admin.entities.save')->defaults('label', 'Save Entity');

    Route::get('/entities/edit/{id}', [EntityController::class, 'edit'])
        ->name('admin.entities.edit')->defaults('label', 'Edit Entity');

    Route::get('/entities/delete/{id}', [EntityController::class, 'delete'])
        ->name('admin.entities.delete')->defaults('label', 'Delete Entity');

    Route::post('/entities/massDelete', [EntityController::class, 'massDelete'])
        ->name('admin.entities.massDelete')->defaults('label', 'Mass Delete Entity');

    Route::post('/entities/export', [EntityController::class, 'massExport'])
        ->name('admin.entities.export')->defaults('label', 'Mass Export Entities');
});