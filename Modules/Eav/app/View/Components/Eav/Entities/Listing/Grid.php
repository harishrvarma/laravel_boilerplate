<?php

namespace Modules\Eav\View\Components\Eav\Entities\Listing;

use Modules\Eav\Models\Eav\Entity\Type;
use Modules\Core\View\Components\Listing\Grid as CoreGrid;

class Grid extends CoreGrid
{
    public function __construct()
    {
        parent::__construct();
        $this->title('Manage Entity Types');
    }

    public function prepareColumns()
    {
        // Mass selection checkbox
        if (canAccess('admin.system.eav.entities.massDelete')) {
            $this->column('mass_ids', [
                'name' => 'entity_type_id',
                'label' => '',
                'columnClassName' => '\Modules\Core\View\Components\Listing\Grid\Columns\MassIds',
            ]);
        }

        // ID
        $this->column('entity_type_id', [
            'name' => 'entity_type_id',
            'label' => 'ID',
            'sortable' => true,
        ]);

        // Code
        $this->column('code', [
            'name' => 'code',
            'label' => 'Code',
            'sortable' => true,
        ]);

        // Name
        $this->column('name', [
            'name' => 'name',
            'label' => 'Name',
            'sortable' => true,
        ]);

        $this->column('model_class', [
            'name' => 'model_class',
            'label' => 'Model Class',
            'sortable' => true,
        ]);

        // Created At
        $this->column('created_at', [
            'name' => 'created_at',
            'label' => 'Created At',
            'sortable' => true,
        ]);

        // Updated At
        $this->column('updated_at', [
            'name' => 'updated_at',
            'label' => 'Updated At',
            'sortable' => true,
        ]);

        return $this;
    }

    public function prepareActions()
    {
        if (canAccess('admin.system.eav.entities.edit')) {
            $this->action('edit', [
                'id' => 'editBtn',
                'title' => 'Edit',
                'url' => 'admin.system.eav.entities.edit'
            ]);
        }

        if (canAccess('admin.system.eav.entities.delete')) {
            $this->action('delete', [
                'id' => 'deleteBtn',
                'title' => 'Delete',
                'url' => 'admin.system.eav.entities.delete'
            ]);
        }

        if (canAccess('admin.system.eav.entities.structure')) {
            $this->action('structure', [
                'id' => 'structureBtn',
                'title' => 'Prepare Module Structure',
                'url' => 'admin.system.eav.entities.structure'
            ]);
        }

        if (canAccess('admin.system.eav.attributes.config.listing')) {
            $this->action('config', [
                'id' => 'configBtn',
                'title' => 'Config',
                'url' => 'admin.system.eav.attributes.config.listing',
            ]);
        }
        
        return $this;
    }

    public function prepareFilters()
    {
        $this->filter('code', [
            'name' => 'code',
            'type' => 'text',
        ]);

        $this->filter('name', [
            'name' => 'name',
            'type' => 'text',
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
        if (canAccess('admin.system.eav.entities.massDelete')) {
            parent::prepareMassActions();
            $this->massAction('delete', [
                'value' => 'mass_delete',
                'label' => 'Delete Selected',
                'url'   => 'admin.entity_types.massDelete',
            ]);
        }

        if (canAccess('admin.system.eav.entities.export')) {
            $this->massAction('export', [
                'value' => 'mass_export',
                'label' => 'Export',
                'url'   => 'admin.entity_types.export',
            ]);
        }
    }

    public function prepareCollection()
    {
        $this->gridKey = 'EntityTypes';
        $model = $this->model(Type::class);
        $query = $model->query();

        if ($this->sortColumn() && $this->sortDir()) {
            $query->orderBy($this->sortColumn(), $this->sortDir());
        }

        $this->applyFilters($query);

        $hiddenColumns = $this->handleHiddenColumns($model->getKeyName());
        if (!empty($hiddenColumns)) {
            $allColumns = array_values(array_diff(array_keys($this->columns()), ['mass_ids']));
            $visibleColumns = array_diff($allColumns, $hiddenColumns);
            $query->select($visibleColumns);
        }

        $this->pager($query);

        return $this;
    }

    public function prepareButtons()
    {
        if (canAccess('admin.system.eav.entities.add')) {
            $this->button('add', [
                'route' => urlx('admin.system.eav.entities.add', [], true),
                'label' => 'Add Entity Type',
            ]);
        }
        return $this;
    }
}
