<?php
namespace Modules\Admin\View\Components\Role\Listing;

use Modules\Admin\Models\Role;
use Modules\Core\View\Components\Listing\Grid as CoreGrid;
class Grid extends CoreGrid
{
    public function prepareColumns()
    {
        $this->column('mass_ids', [
            'name'=>'id',
            'label'=>'',
            'renderer'=>'core::listing.grid.columns.massIds',
        ]);

        $this->column('id', [
            'name'=>'id',
            'label'=>'Id',
            'sortable'=>true,
        ]);

        $this->column('name', [
            'name'=>'name',
            'label'=>'Name',
            'sortable'=>true,
        ]);

        $this->column('created_at', [
            'name'=>'created_at',
            'label'=>'Created Date',
            'sortable'=>true,
        ]);
        return $this;
    }

    public function prepareActions()
    {
        $this->action('edit' , [
            'id' => 'editBtn',
            'title' => 'Edit',
            'url' => 'admin.role.edit'
        ]);
        $this->action('delete' , [
            'id' => 'deleteBtn',
            'title' => 'Delete',
            'url' => 'admin.role.delete'
        ]);
        return $this;
    }

    public function prepareMassActions(){
        parent::prepareMassActions();
        $this->massAction('delete', [
            'value'=>'mass_delete',
            'label' =>'Delete Selected',
            'url' => 'admin.role.massDelete',
        ]);
        $this->massAction('export', [
            'value'=>'mass_export',
            'label' =>'Export Selected',
            'url' => 'admin.role.massExport',
        ]);
    }

    public function prepareFilters()
    {
        $this->filter('name', [
            'type'  => 'text',
            'label' => 'Name',
        ]);

        $this->filter('created_at', [
            'type'  => 'date',
            'label' => 'Created Date',
        ]);

        return $this;
    }
    
    public function prepareCollection() 
    {
        $role = $this->model(Role::class);
        $query = $role->query();
        if($this->sortColumn() && $this->sortDir()){
             $query->orderBy($this->sortColumn(), $this->sortDir());
        }
        $this->applyFilters($query);
        $this->pager($query);
        return $this;
    }
}