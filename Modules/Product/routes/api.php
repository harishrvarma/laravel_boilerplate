<?php


use Modules\Product\Http\Controllers\Api\V1\Entity\Attribute\Value\ValueController;
use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\Api\V1\Entity\EntityController;

Route::middleware(['auth:api'])->prefix('v1/product/entity')->group(function () {
    Route::get('/listing', [EntityController::class,'listing'])->name('api.product.entity.listing')->middleware('scope:api.product.entity.listing')->defaults('label', 'Entity Listing');
    Route::post('/save', [EntityController::class,'save'])->name('api.product.entity.save')->middleware('scope:api.product.entity.save')->defaults('label', 'Save Entity');
    Route::post('/delete', [EntityController::class,'delete'])->name('api.product.entity.delete')->middleware('scope:api.product.entity.delete')->defaults('label', 'Delete Entity');
});


Route::middleware(['auth:api'])->prefix('v1/product/entity/attribute/value')->group(function () {
    Route::get('/listing', [ValueController::class,'listing'])->name('api.product.entity.attribute.value.listing')->middleware('scope:api.product.entity.attribute.value.listing')->defaults('label', 'Value Listing');
    Route::post('/save', [ValueController::class,'save'])->name('api.product.entity.attribute.value.save')->middleware('scope:api.product.entity.attribute.value.save')->defaults('label', 'Save Value');
    Route::post('/delete', [ValueController::class,'delete'])->name('api.product.entity.attribute.value.delete')->middleware('scope:api.product.entity.attribute.value.delete')->defaults('label', 'Delete Value');
});

