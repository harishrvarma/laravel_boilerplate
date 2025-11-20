<?php
namespace Modules\Api\View\Components\ApiUser\Listing;

use Modules\Api\Models\ApiUser;
use Modules\Core\View\Components\Listing\Grid as CoreGrid;
class Grid extends CoreGrid
{
    public function __construct()
    {
        parent::__construct();
        $this->title('Manage Api Users');
    }

    public function prepareColumns()
    {
        if(canAccess('admin.system.apiuser.massDelete')){
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

        $this->column('email', [
            'name'=>'email',
            'label'=>'Email',
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
        if(canAccess('admin.system.apiuser.edit')){
            $this->action('edit' , [
                'id' => 'editBtn',
                'title' => 'Edit',
                'url' => 'admin.system.apiuser.edit'
            ]);
        }
        if(canAccess('admin.system.apiuser.delete')){
            $this->action('delete' , [
                'id' => 'deleteBtn',
                'title' => 'Delete',
                'url' => 'admin.system.apiuser.delete'
            ]);
        }
        return $this;
    }

    public function prepareMassActions(){
        if(canAccess('admin.system.apiuser.massDelete')){
            parent::prepareMassActions();
            $this->massAction('delete', [
                'value'=>'mass_delete',
                'label' =>'Delete Selected',
                'url' => 'admin.system.apiuser.massDelete',
            ]);
        }

        if (canAccess('admin.system.apiuser.export')) {
            $this->massAction('export', [
                'value' => 'mass_export',
                'label' => 'Export',
                'url' => 'admin.system.apiuser.export',
            ]);
        }
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
        $this->gridKey = 'Api_User';
        $apiUser = $this->model(ApiUser::class);
        $query = $apiUser->query();
        if($this->sortColumn() && $this->sortDir()){
             $query->orderBy($this->sortColumn(), $this->sortDir());
        }
        $this->applyFilters($query);
        $hiddenColumns = $this->handleHiddenColumns($apiUser->getKeyName());
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
        if(canAccess('admin.system.apiuser.add')){
            $this->button('add', [
                'route' =>urlx('admin.system.apiuser.add',[],true),
                'label' => 'Add Api User',
            ]);
        }
        return $this;
    }
}