<?php

use Illuminate\Support\Facades\Route;
use Modules\Events\Http\Controllers\Api\V1\EventsController;

Route::middleware(['auth:api'])->prefix('v1/event')->group(function () {
    Route::get('/listing', [EventsController::class,'listing'])->name('event.listing')->middleware('scope:api.event.listing')->defaults('label', 'Event Listing');
    Route::post('/save',[EventsController::class,'save'])->name('event.save')->middleware('scope:api.event.listing')->defaults('label', 'Edit Event');
    Route::post('/delete',[EventsController::class,'delete'])->name('event.delete')->middleware('scope:api.event.listing')->defaults('label', 'Delete Event');
});
