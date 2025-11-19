<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Middleware\AdminAuthenticate;
use Modules\Admin\Http\Middleware\AdminPermission;
use Modules\Eav\Http\Controllers\Eav\AttributeController;
use Modules\Eav\Http\Controllers\Eav\Attribute\GroupController;
use Modules\Eav\Http\Controllers\Eav\Attribute\ConfigController;
use Modules\Eav\Http\Controllers\Eav\EntityController;

Route::prefix('admin')
    ->middleware([AdminAuthenticate::class, AdminPermission::class])
    ->group(function () {

        /** -------------------------
         *  ATTRIBUTE ROUTES
         * --------------------------*/
        Route::match(['GET', 'POST'], 'eav/attributes/listing', [AttributeController::class, 'listing'])
            ->name('admin.system.eav.attributes.listing')->defaults('label', 'Attributes Listing');

        Route::get('eav/attributes/add', [AttributeController::class, 'add'])
            ->name('admin.system.eav.attributes.add')->defaults('label', 'Add Attribute');

        Route::post('eav/attributes/save', [AttributeController::class, 'save'])
            ->name('admin.system.eav.attributes.save')->defaults('label', 'Save Attribute');

        Route::get('eav/attributes/edit/{id}', [AttributeController::class, 'edit'])
            ->name('admin.system.eav.attributes.edit')->defaults('label', 'Edit Attribute');

        Route::get('eav/attributes/delete/{id}', [AttributeController::class, 'delete'])
            ->name('admin.system.eav.attributes.delete')->defaults('label', 'Delete Attribute');

        Route::post('eav/attributes/mass-delete', [AttributeController::class, 'massDelete'])
            ->name('admin.system.eav.attributes.massDelete')->defaults('label', 'Mass Delete Attributes');

        Route::post('eav/attributes/export', [AttributeController::class, 'massExport'])
            ->name('admin.system.eav.attributes.export')->defaults('label', 'Mass Export Attributes');

        Route::get('eav/attributes/byentity/{entityTypeId}', [AttributeController::class, 'getGroupsByEntity'])
            ->name('admin.system.eav.attributes.byEntity')->defaults('label', 'get Group Options By Entity');

        /** -------------------------
         *  ATTRIBUTE GROUP ROUTES
         * --------------------------*/

         Route::match(['GET', 'POST'], 'eav/attributes/group/listing', [GroupController::class, 'listing'])
            ->name('admin.system.eav.attributes.group.listing')->defaults('label', 'Attribute Group Listing');

        Route::get('eav/attributes/group/add', [GroupController::class, 'add'])
            ->name('admin.system.eav.attributes.group.add')->defaults('label', 'Add Attribute Group');

        Route::post('eav/attributes/group/save', [GroupController::class, 'save'])
            ->name('admin.system.eav.attributes.group.save')->defaults('label', 'Save Attribute Group');

        Route::get('eav/attributes/group/edit/{id}', [GroupController::class, 'edit'])
            ->name('admin.system.eav.attributes.group.edit')->defaults('label', 'Edit Attribute Group');

        Route::get('eav/attributes/group/delete/{id}', [GroupController::class, 'delete'])
            ->name('admin.system.eav.attributes.group.delete')->defaults('label', 'Delete Attribute Group');

        Route::post('eav/attributes/group/mass-delete', [GroupController::class, 'massDelete'])
            ->name('admin.system.eav.attributes.group.massDelete')->defaults('label', 'Mass Delete Attribute Groups');

        Route::post('eav/attributes/group/export', [GroupController::class, 'massExport'])
            ->name('admin.system.eav.attributes.group.export')->defaults('label', 'Mass Export Attribute Groups');

        
        /** -------------------------
         *  ATTRIBUTE CONFIG ROUTES
         * --------------------------*/


        Route::match(['GET', 'POST'], 'eav/attributes/config/listing', [ConfigController::class, 'listing'])
            ->name('admin.system.eav.attributes.config.listing')->defaults('label', 'Attribute config Listing');

        Route::post('eav/attributes/config/save', [ConfigController::class, 'save'])
            ->name('admin.system.eav.attributes.config.save')->defaults('label', 'Save Attribute config');

        /** -------------------------
         *  ENTITY ROUTES
         * --------------------------*/
        Route::match(['GET', 'POST'], 'eav/entities/listing', [EntityController::class, 'listing'])
            ->name('admin.system.eav.entities.listing')->defaults('label', 'Entities Listing');

        Route::get('eav/entities/add', [EntityController::class, 'add'])
            ->name('admin.system.eav.entities.add')->defaults('label', 'Add Entity');

        Route::post('eav/entities/save', [EntityController::class, 'save'])
            ->name('admin.system.eav.entities.save')->defaults('label', 'Save Entity');

        Route::get('eav/entities/edit/{id}', [EntityController::class, 'edit'])
            ->name('admin.system.eav.entities.edit')->defaults('label', 'Edit Entity');

        Route::get('eav/entities/delete/{id}', [EntityController::class, 'delete'])
            ->name('admin.system.eav.entities.delete')->defaults('label', 'Delete Entity');

        Route::post('eav/entities/mass-delete', [EntityController::class, 'massDelete'])
            ->name('admin.system.eav.entities.massDelete')->defaults('label', 'Mass Delete Entities');

        Route::post('eav/entities/export', [EntityController::class, 'massExport'])
            ->name('admin.system.eav.entities.export')->defaults('label', 'Mass Export Entities');

        Route::get('eav/entities/structure/{id}', [EntityController::class, 'structure'])
            ->name('admin.system.eav.entities.structure')->defaults('label', 'Prepare Module Structure');
    });
