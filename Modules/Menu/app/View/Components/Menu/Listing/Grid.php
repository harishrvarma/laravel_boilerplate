<?php
namespace Modules\Menu\View\Components\Menu\Listing;

use Modules\Menu\Models\Menu;
use Modules\Core\View\Components\Listing\Grid as CoreGrid;
class Grid extends CoreGrid
{
    public function __construct()
    {
        parent::__construct();
        $this->title('Manage Menus');
    }

    public function prepareColumns()
    {
        if (canAccess('admin.menu.massDelete')) {
            $this->column('mass_ids', [
                'name' => 'id',
                'label' => '',
                'columnClassName' => '\Modules\Core\View\Components\Listing\Grid\Columns\MassIds',
            ]);
        }

        $this->column('id', [
            'name' => 'id',
            'label' => 'ID',
            'sortable' => true,
        ]);

        $this->column('title', [
            'name' => 'title',
            'label' => 'Title',
            'sortable' => true,
        ]);

        $this->column('item_type', [
            'name' => 'item_type',
            'label' => 'Type',
            'sortable' => true,
        ]);

        $this->column('icon', [
            'name' => 'icon',
            'label' => 'Icon',
        ]);

        $this->column('area', [
            'name' => 'area',
            'label' => 'Area',
            'sortable' => true,
        ]);

        $this->column('order_no', [
            'name' => 'order_no',
            'label' => 'Order',
            'sortable' => true,
        ]);

        $this->column('is_active', [
            'name' => 'is_active',
            'label' => 'Active',
        ]);

        $this->column('created_at', [
            'name' => 'created_at',
            'label' => 'Created Date',
            'sortable' => true,
        ]);

        return $this;
    }

    public function prepareActions()
    {
        if (canAccess('admin.menu.edit')) {
            $this->action('edit', [
                'id' => 'editBtn',
                'title' => 'Edit',
                'url' => 'admin.menu.edit'
            ]);
        }

        if (canAccess('admin.menu.delete')) {
            $this->action('delete', [
                'id' => 'deleteBtn',
                'title' => 'Delete',
                'url' => 'admin.menu.delete'
            ]);
        }

        return $this;
    }

    public function prepareFilters()
    {
        $this->filter('title', [
            'name' => 'title',
            'type' => 'text',
        ]);

        $this->filter('item_type', [
            'name' => 'item_type',
            'type' => 'select',
            'options' => [
                '' => 'All',
                'folder' => 'Folder',
                'file' => 'File',
            ],
        ]);

        $this->filter('area', [
            'name' => 'area',
            'type' => 'select',
            'options' => [
                '' => 'All',
                'admin' => 'Admin',
                'api' => 'API',
                'frontend' => 'Frontend',
            ],
        ]);

        $this->filter('is_active', [
            'name' => 'is_active',
            'type' => 'select',
            'options' => [
                '' => 'All',
                1 => 'Active',
                0 => 'Inactive',
            ],
        ]);

        $this->filter('created_at', [
            'name' => 'created_at',
            'type' => 'date',
        ]);

        return $this;
    }

    public function prepareMassActions()
    {
        if (canAccess('admin.menu.massDelete')) {
            parent::prepareMassActions();
            $this->massAction('delete', [
                'value' => 'mass_delete',
                'label' => 'Delete Selected',
                'url' => 'admin.menu.massDelete',
            ]);
        }
        if (canAccess('admin.menu.export')) {
            $this->massAction('export', [
                'value' => 'mass_export',
                'label' => 'Export',
                'url' => 'admin.menu.export',
            ]);
        }
    }

    public function prepareCollection()
    {
        $menu = $this->model(Menu::class);
        $query = $menu->query();

        if ($this->sortColumn() && $this->sortDir()) {
            $query->orderBy($this->sortColumn(), $this->sortDir());
        }

        $this->applyFilters($query);
        $this->pager($query);

        return $this;
    }

    public function prepareButtons()
    {
        if (canAccess('admin.menu.add')) {
            $this->button('add', [
                'route' => urlx('admin.menu.add', [], true),
                'label' => 'Add Menu',
            ]);
        }

        if (canAccess('admin.menu.tree')) {
            $this->button('tree', [
                'route' => urlx('admin.menu.tree', [], true),
                'label' => 'Manage Tree',
            ]);
        }

        return $this;
    }
}