<?php

use Illuminate\Support\Facades\Route;
use Modules\Events\Http\Controllers\EventsController;
use Modules\Admin\Http\Middleware\AdminAuthenticate;
use Modules\Events\Http\Controllers\ListenerController;
use Modules\Admin\Http\Middleware\AdminPermission;


Route::prefix('admin')->group(function(){
    
    Route::middleware([AdminAuthenticate::class,AdminPermission::class])->group(function () {
        Route::match(['GET','POST'], '/event/listing', [EventsController::class,'listing'])
        ->name('admin.system.event.listing')->defaults('label', 'Event Listing');
        Route::get('/event/add', [EventsController::class,'add'])->name('admin.system.event.add')->defaults('label', 'Add Event');
        Route::post('/event/save', [EventsController::class,'save'])->name('admin.system.event.save')->defaults('label', 'Save Event');
        Route::get('/event/edit/{id}', [EventsController::class, 'edit'])->name('admin.system.event.edit')->defaults('label', 'Edit Event');
        Route::get('/event/delete/{id}', [EventsController::class,'delete'])->name('admin.system.event.delete')->defaults('label', 'Delete Event');
        Route::post('/event/massDelete', [EventsController::class,'massDelete'])->name('admin.system.event.massDelete')->defaults('label', 'Mass Delete Event');
        Route::post('/event/export', [EventsController::class,'massExport'])->name('admin.system.event.export')->defaults('label', 'Mass Export Event');

        Route::match(['GET','POST'], '/listener/listing/{event_id}', [ListenerController::class,'listing'])
        ->name('admin.system.listener.listing')->defaults('label', 'Event Listener Listing');
        Route::get('/listener/add/{event_id}', [ListenerController::class,'add'])->name('admin.system.listener.add')->defaults('label', 'Add Event Listener');
        Route::post('/listener/save/{id}/{event_id}', [ListenerController::class,'save'])->name('admin.system.listener.save')->defaults('label', 'Save Event Listener');
        Route::get('/listener/edit/{id}', [ListenerController::class, 'edit'])->name('admin.system.listener.edit')->defaults('label', 'Edit Event Listener');
        Route::get('/listener/delete/{id}', [ListenerController::class,'delete'])->name('admin.system.listener.delete')->defaults('label', 'Delete Event Listener');
    });
});