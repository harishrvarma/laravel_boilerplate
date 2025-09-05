<?php
namespace Modules\ApiService\View\Components\ApiUser\Listing;

use Modules\ApiService\Models\ApiUser;
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
        if(canAccess('admin.apiuser.massDelete')){
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
        if(canAccess('admin.apiuser.edit')){
            $this->action('edit' , [
                'id' => 'editBtn',
                'title' => 'Edit',
                'url' => 'admin.apiuser.edit'
            ]);
        }
        if(canAccess('admin.apiuser.delete')){
            $this->action('delete' , [
                'id' => 'deleteBtn',
                'title' => 'Delete',
                'url' => 'admin.apiuser.delete'
            ]);
        }
        return $this;
    }

    public function prepareMassActions(){
        if(canAccess('admin.apiuser.massDelete')){
            parent::prepareMassActions();
            $this->massAction('delete', [
                'value'=>'mass_delete',
                'label' =>'Delete Selected',
                'url' => 'admin.apiuser.massDelete',
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
        $apiUser = $this->model(ApiUser::class);
        $query = $apiUser->query();
        if($this->sortColumn() && $this->sortDir()){
             $query->orderBy($this->sortColumn(), $this->sortDir());
        }
        $this->applyFilters($query);
        $this->pager($query);
        return $this;
    }

    public function prepareButtons()
    {
        if(canAccess('admin.apiuser.add')){
            $this->button('add', [
                'route' =>urlx('admin.apiuser.add',[],true),
                'label' => 'Add Api User',
            ]);
        }
        return $this;
    }
}