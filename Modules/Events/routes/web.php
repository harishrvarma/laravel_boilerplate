<?php

use Illuminate\Support\Facades\Route;
use Modules\Events\Http\Controllers\EventsController;
use Modules\Admin\Http\Middleware\AdminAuthenticate;
use Modules\Events\Http\Controllers\ListenerController;

Route::prefix('admin')->group(function(){
    
    Route::middleware(AdminAuthenticate::class)->group(function () {
        Route::match(['GET','POST'], '/event/listing', [EventsController::class,'listing'])
        ->name('admin.event.listing');
        Route::get('/event/add', [EventsController::class,'add'])->name('admin.event.add');
        Route::post('/event/save', [EventsController::class,'save'])->name('admin.event.save');
        Route::get('/event/edit/{id}', [EventsController::class, 'edit'])->name('admin.event.edit');
        Route::get('/event/delete/{id}', [EventsController::class,'delete'])->name('admin.event.delete');
        Route::post('/event/massDelete', [EventsController::class,'massDelete'])->name('admin.event.massDelete');


        Route::get('/listener/add/{event_id}', [ListenerController::class,'add'])->name('admin.listener.add');
        Route::post('/listener/save/{id}/{event_id}', [ListenerController::class,'save'])->name('admin.listener.save');
        Route::get('/listener/edit/{id}', [ListenerController::class, 'edit'])->name('admin.listener.edit');
        Route::get('/listener/delete/{id}', [ListenerController::class,'delete'])->name('admin.listener.delete');
    });
});