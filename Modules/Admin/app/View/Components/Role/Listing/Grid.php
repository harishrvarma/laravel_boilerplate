<?php
namespace Modules\Admin\View\Components\Role\Listing;

use Modules\Admin\Models\Role;
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
        if(canAccess('admin.role.massDelete')){
            $this->column('mass_ids', [
                'name'=>'id',
                'label'=>'',
                'columnClassName'=>'\Modules\Core\View\Components\Listing\Grid\Columns\MassIds',
            ]);
        }
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
        if(canAccess('admin.role.edit')){
            $this->action('edit' , [
                'id' => 'editBtn',
                'title' => 'Edit',
                'url' => 'admin.role.edit'
            ]);
        }
        if(canAccess('admin.role.delete')){
            $this->action('delete' , [
                'id' => 'deleteBtn',
                'title' => 'Delete',
                'url' => 'admin.role.delete'
            ]);
        }
        return $this;
    }

    public function prepareMassActions(){
        if(canAccess('admin.role.massDelete')){
            parent::prepareMassActions();
            $this->massAction('delete', [
                'value'=>'mass_delete',
                'label' =>'Delete Selected',
                'url' => 'admin.role.massDelete',
            ]);
        }
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
        if(canAccess('admin.role.add')){
            $this->button('add', [
                'label' => 'Add Role',
                'route' =>urlx('admin.role.add',[],true),
            ]);
        }
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