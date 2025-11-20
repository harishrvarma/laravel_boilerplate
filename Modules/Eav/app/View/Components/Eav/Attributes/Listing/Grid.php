<?php

namespace Modules\Eav\View\Components\Eav\Attributes\Listing;

use Modules\Eav\Models\Eav\Attribute;
use Modules\Core\View\Components\Listing\Grid as CoreGrid;

class Grid extends CoreGrid
{
    public function __construct()
    {
        parent::__construct();
        $this->title('Manage Attributes');
    }

    public function prepareColumns()
    {
        if(canAccess('admin.system.eav.attributes.massDelete')){
            $this->column('mass_ids', [
                'name' => 'attribute_id',
                'label' => '',
                'columnClassName' => '\Modules\Core\View\Components\Listing\Grid\Columns\MassIds',
            ]);
        }

        $this->column('attribute_id', [
            'name' => 'attribute_id',
            'label' => 'Attribute id',
            'sortable' => true,
        ]);

        $this->column('entity_type_name', [
            'name' => 'entity_type_name',
            'label' => 'Entity type',
            'sortable' => false,
        ]);

        $this->column('group_name', [
            'name' => 'group_name',
            'label' => 'Group',
            'sortable' => false,
        ]);

        $this->column('code', [
            'name' => 'code',
            'label' => 'Code',
            'sortable' => true,
        ]);

        $this->column('data_type', [
            'name' => 'data_type',
            'label' => 'Data type',
            'sortable' => true,
        ]);

        $this->column('data_model', [
            'name' => 'data_model',
            'label' => 'Data model',
            'sortable' => true,
        ]);

        $this->column('is_required', [
            'name' => 'is_required',
            'label' => 'Is required',
            'sortable' => true,
        ]);

        $this->column('position', [
            'name' => 'position',
            'label' => 'Position',
            'sortable' => true,
        ]);

        $this->column('lang_type', [
            'name' => 'lang_type',
            'label' => 'Lang type',
            'sortable' => true,
        ]);

        $this->column('created_at', [
            'name' => 'created_at',
            'label' => 'Created at',
            'sortable' => true,
        ]);

        $this->column('updated_at', [
            'name' => 'updated_at',
            'label' => 'Updated at',
            'sortable' => true,
        ]);

        return $this;
    }

    public function prepareActions()
    {
        if(canAccess('admin.system.eav.attributes.edit')){
            $this->action('edit', [
                'id' => 'editBtn',
                'title' => 'Edit',
                'url' => 'admin.system.eav.attributes.edit'
            ]);
        }

        if(canAccess('admin.system.eav.attributes.delete')){
            $this->action('delete', [
                'id' => 'deleteBtn',
                'title' => 'Delete',
                'url' => 'admin.system.eav.attributes.delete'
            ]);
        }

        return $this;
    }

    public function prepareFilters()
    {
        $this->filter('entity_type_id', [
                'name' => 'entity_type_id',
                'type' => 'text',
            ]);

        $this->filter('code', [
                'name' => 'code',
                'type' => 'text',
            ]);

        $this->filter('data_type', [
            'name' => 'data_type',
            'type' => 'text',
        ]);

        $this->filter('data_model', [
            'name' => 'data_model',
            'type' => 'text',
        ]);

        $this->filter('is_required', [
            'name' => 'is_required',
            'type' => 'select',
            'options' => [
                1 => 'Yes',
                2 => 'No',
            ]
        ]);

        $this->filter('position', [
            'name' => 'position',
            'type' => 'text',
        ]);

        $this->filter('default_value', [
            'name' => 'default_value',
            'type' => 'text',
        ]);

        $this->filter('is_translatable', [
            'name' => 'is_translatable',
            'type' => 'select',
            'options' => [
                1 => 'Yes',
                2 => 'No',
            ]
        ]);

        $this->filter('created_at', [
            'name' => 'created_at',
            'type' => 'date',
        ]);

        $this->filter('updated_at', [
            'name' => 'updated_at',
            'type' => 'date',
        ]);

        return $this;
    }

    public function prepareMassActions()
    {
        if (canAccess('admin.system.eav.attributes.massDelete')) {
            parent::prepareMassActions();
            $this->massAction('delete', [
                'value' => 'mass_delete',
                'label' => 'Delete Selected',
                'url'   => 'admin.system.eav.attributes.massDelete',
            ]);
        }

        if (canAccess('admin.system.eav.attributes.export')) {
            $this->massAction('export', [
                'value' => 'mass_export',
                'label' => 'Export',
                'url' => 'admin.system.eav.attributes.export',
            ]);
        }
    }
    
    public function prepareCollection()
    {
        $this->moduleName = 'Attributes';
        $model = $this->model(Attribute::class);
    
        $query = Attribute::with([
            'entityType:entity_type_id,name',
            'group:group_id,code'
        ]);
    
        $allColumns = collect($this->columns())
            ->pluck('name')
            ->filter(fn($col) => $col && !in_array($col, ['mass_ids', 'entity_type_name', 'group_name']))
            ->values()
            ->all();
    
        $hiddenColumns = $this->handleHiddenColumns($model->getKeyName());
        if (!empty($hiddenColumns)) {
            $allColumns = array_diff($allColumns, $hiddenColumns);
        }
    
        $primaryKey = $model->getKeyName();
    
        $requiredForeignKeys = ['entity_type_id', 'group_id'];
        $allColumns = array_unique(array_merge($allColumns, $requiredForeignKeys, [$primaryKey]));
        
        $query->select($allColumns);
    
        $sortColumn = $this->sortColumn();
        $sortDir    = $this->sortDir();
    
        if ($sortColumn && in_array($sortColumn, $allColumns)) {
            $query->orderBy($sortColumn, $sortDir);
        }
    
        $this->applyFilters($query);
        $this->pager($query);
    
        return $this;
    }

    public function prepareButtons()
    {
        if (canAccess('admin.system.eav.attributes.add')) {
            $this->button('add', [
                'route' => urlx('admin.system.eav.attributes.add', [], true),
                'label' => 'Add Attributes',
            ]);
        }
        return $this;
    }
}