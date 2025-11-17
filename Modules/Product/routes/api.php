<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\Api\V1\EntityController;

Route::middleware(['auth:api'])->prefix('v1/product/entity')->group(function () {
    Route::get('/listing', [EntityController::class,'listing'])->name('product.entity.listing')->middleware('scope:api.product.entity.listing')->defaults('label', 'Entity Listing');
    Route::post('/save', [EntityController::class,'save'])->name('product.entity.save')->middleware('scope:api.product.entity.save')->defaults('label', 'Save Entity');
    Route::post('/delete', [EntityController::class,'delete'])->name('product.entity.delete')->middleware('scope:api.product.entity.delete')->defaults('label', 'Delete Entity');
});