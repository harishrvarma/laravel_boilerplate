<?php

namespace Modules\Eav\View\Components\Eav\Attributes\Group\Listing;

use Modules\Eav\Models\Eav\Attribute\Group;
use Modules\Core\View\Components\Listing\Grid as CoreGrid;

class Grid extends CoreGrid
{
    public function __construct()
    {
        parent::__construct();
        $this->title('Manage Attribute Groups');
    }

    /**
     * Prepare table columns
     */
    public function prepareColumns()
    {
        if (canAccess('admin.system.eav.attributes.group.massDelete')) {
            $this->column('mass_ids', [
                'name' => 'group_id',
                'label' => '',
                'columnClassName' => '\Modules\Core\View\Components\Listing\Grid\Columns\MassIds',
            ]);
        }

        $this->column('group_id', [
            'name' => 'group_id',
            'label' => 'Group ID',
            'sortable' => true,
        ]);

        $this->column('entity_type_name', [
            'name' => 'entity_type_name',
            'label' => 'Entity type',
            'sortable' => false,
        ]);

        $this->column('code', [
            'name' => 'code',
            'label' => 'Code',
            'sortable' => true,
        ]);


        $this->column('position', [
            'name' => 'position',
            'label' => 'Position',
            'sortable' => true,
        ]);

        $this->column('created_at', [
            'name' => 'created_at',
            'label' => 'Created At',
            'sortable' => true,
        ]);

        $this->column('updated_at', [
            'name' => 'updated_at',
            'label' => 'Updated At',
            'sortable' => true,
        ]);

        return $this;
    }

    /**
     * Prepare row-level actions
     */
    public function prepareActions()
    {
        if (canAccess('admin.system.eav.attributes.group.edit')) {
            $this->action('edit', [
                'id' => 'editBtn',
                'title' => 'Edit',
                'url' => 'admin.system.eav.attributes.group.edit',
            ]);
        }

        if (canAccess('admin.system.eav.attributes.group.delete')) {
            $this->action('delete', [
                'id' => 'deleteBtn',
                'title' => 'Delete',
                'url' => 'admin.system.eav.attributes.group.delete',
            ]);
        }

        return $this;
    }

    /**
     * Prepare filter fields
     */
    public function prepareFilters()
    {
        $this->filter('code', [
            'name' => 'code',
            'type' => 'text',
        ]);

        $this->filter('position', [
            'name' => 'position',
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

    /**
     * Prepare mass actions
     */
    public function prepareMassActions()
    {
        if (canAccess('admin.system.eav.attributes.group.massDelete')) {
            parent::prepareMassActions();
            $this->massAction('delete', [
                'value' => 'mass_delete',
                'label' => 'Delete Selected',
                'url'   => 'admin.system.eav.attributes.group.massDelete',
            ]);
        }

        if (canAccess('admin.system.eav.attributes.group.export')) {
            $this->massAction('export', [
                'value' => 'mass_export',
                'label' => 'Export',
                'url'   => 'admin.system.eav.attributes.group.export',
            ]);
        }
    }

    /**
     * Prepare collection (query builder)
     */
    public function prepareCollection()
    {
        $this->moduleName = 'Attribute Groups';
        $model = $this->model(Group::class);
        $query = $model->with('translations', 'attributes', 'entityType');

        // Sorting
        if ($this->sortColumn() && $this->sortDir()) {
            $query->orderBy($this->sortColumn(), $this->sortDir());
        }

        // Filters
        $this->applyFilters($query);

        // Hidden column handling
        $hiddenColumns = $this->handleHiddenColumns($model->getKeyName());
        if (!empty($hiddenColumns)) {
            $allColumns = array_values(array_diff(array_keys($this->columns()), ['mass_ids']));
            $visibleColumns = array_diff($allColumns, $hiddenColumns);
            $query->select($visibleColumns);
        }

        $this->pager($query);
        return $this;
    }

    /**
     * Prepare toolbar buttons
     */
    public function prepareButtons()
    {
        if (canAccess('admin.system.eav.attributes.group.add')) {
            $this->button('add', [
                'route' => urlx('admin.system.eav.attributes.group.add', [], true),
                'label' => 'Add Attribute Group',
            ]);
        }

        return $this;
    }
}
