<?php
namespace Modules\Admin\View\Components\Admin\Listing;

use Modules\Admin\Models\Admin;
use Modules\Core\View\Components\Listing\Grid as CoreGrid;
class Grid extends CoreGrid
{

    public function __construct()
    {
        parent::__construct();
        $this->title('Manage Admins');
    }

    public function prepareColumns()
    {
        $this->column('mass_ids', [
            'name'=>'id',
            'label'=>'',
            'columnClassName'=>'\Modules\Core\View\Components\Listing\Grid\Columns\MassIds',
        ]);

        $this->column('id', [
            'name'=>'id',
            'label'=>'Id',
            'sortable'=>true,
        ]);

        $this->column('first_name', [
            'name'=>'first_name',
            'label'=>'First Name',
            'sortable'=>true,
        ]);

        $this->column('last_name', [
            'name'=>'last_name',
            'label'=>'Last Name',
            'sortable'=>true,
        ]);

        $this->column('email', [
            'name'=>'email',
            'label'=>'email',
            'sortable'=>true,
        ]);
        $this->column('phone', [
            'name'=>'phone',
            'label'=>'Phone',
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
            'url' => 'admin.admin.edit'
        ]);
        $this->action('delete' , [
            'id' => 'deleteBtn',
            'title' => 'Delete',
            'url' => 'admin.admin.delete'
        ]);
        return $this;
    }

    public function prepareFilters()
    {
        $this->filter('first_name', [
            'name' => 'first_name',
            'type'  => 'text',
        ]);

        $this->filter('last_name', [
            'type'  => 'text',
            'name' => 'last_name',
        ]);

        $this->filter('email', [
            'type'  => 'text',
            'name' => 'email',
        ]);

        $this->filter('phone', [
            'type'  => 'text',
            'name' => 'phone',
        ]);

        $this->filter('created_at', [
            'type'  => 'date',
            'name' => 'created_at',
        ]);

        return $this;
    }

    public function prepareMassActions(){
        parent::prepareMassActions();
        $this->massAction('delete', [
            'value'=>'mass_delete',
            'label' =>'Delete Selected',
            'url' => 'admin.admin.massDelete',
        ]);
        $this->massAction('export', [
            'value'=>'mass_export',
            'label' =>'Export Selected',
            'url' => 'admin.admin.massExport',
        ]);
    }
    
    public function prepareCollection() 
    {
        $admin = $this->model(Admin::class);
        $query = $admin->query();
        if($this->sortColumn() && $this->sortDir()){
             $query->orderBy($this->sortColumn(), $this->sortDir());
        }
        $this->applyFilters($query);
        $this->pager($query);
        return $this;
    }

    public function prepareButtons()
    {
        $this->button('add', [
            'route' =>urlx('admin.admin.add',[],true),
            'label' => 'Add Admin',
        ]);
        return $this;
    }
}