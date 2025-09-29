<?php

use Illuminate\Support\Facades\Route;
use Modules\Menu\Http\Controllers\MenuController;
use Modules\Admin\Http\Middleware\AdminAuthenticate;
use Modules\Admin\Http\Middleware\AdminPermission;

Route::prefix('admin')->group(function () {

    Route::middleware([AdminAuthenticate::class, AdminPermission::class])->group(function () {
        
        // Menu Listing
        Route::match(['GET', 'POST'], '/menu/listing', [MenuController::class, 'listing'])
            ->name('admin.menu.listing')
            ->defaults('label', 'Menu Listing');

        // Add Menu
        Route::get('/menu/add', [MenuController::class, 'add'])
            ->name('admin.menu.add')
            ->defaults('label', 'Add Menu');

        // Save Menu
        Route::post('/menu/save', [MenuController::class, 'save'])
            ->name('admin.menu.save')
            ->defaults('label', 'Save Menu');

        // Edit Menu
        Route::get('/menu/edit/{id}', [MenuController::class, 'edit'])
            ->name('admin.menu.edit')
            ->defaults('label', 'Edit Menu');

        // Delete Menu
        Route::get('/menu/delete/{id}', [MenuController::class, 'delete'])
            ->name('admin.menu.delete')
            ->defaults('label', 'Delete Menu');

        // Mass Delete Menus
        Route::post('/menu/massDelete', [MenuController::class, 'massDelete'])
            ->name('admin.menu.massDelete')
            ->defaults('label', 'Mass Delete Menus');

        // Tree View (Manage Menu Structure)
        Route::get('/menu/tree', [MenuController::class, 'tree'])
            ->name('admin.menu.tree')
            ->defaults('label', 'Menu Tree View');
        
        Route::post('/menu/saveTree', [MenuController::class, 'saveTree'])
        ->name('admin.menu.saveTree')
        ->defaults('label', 'Save Menu Tree');
        
    });
});
