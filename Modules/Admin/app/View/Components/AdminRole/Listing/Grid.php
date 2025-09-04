<?php
namespace Modules\Admin\View\Components\AdminRole\Listing;

use Modules\Admin\Models\AdminRole;
use Modules\Core\View\Components\Listing\Grid as CoreGrid;
class Grid extends CoreGrid
{
    public function __construct()
    {
        parent::__construct();
        $this->title('Manage Roles');
    }
    public function prepareColumns()
    {
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

        $this->column('description', [
            'name'=>'description',
            'label'=>'Description',
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
            'name' => 'name',
        ]);

        $this->filter('description', [
            'type'  => 'text',
            'name' => 'description',
        ]);

        $this->filter('created_at', [
            'type'  => 'date',
            'name' => 'created_at',
        ]);

        return $this;
    }

    public function prepareButtons(){
        $this->button('add', [
            'label' => 'Add Role',
            'route' =>urlx('admin.role.add',[],true),
        ]);
    }
    
    public function prepareCollection() 
    {
        $role = $this->model(AdminRole::class);
        $query = $role->query();
        if($this->sortColumn() && $this->sortDir()){
             $query->orderBy($this->sortColumn(), $this->sortDir());
        }
        $this->applyFilters($query);
        $this->pager($query);
        return $this;
    }
}