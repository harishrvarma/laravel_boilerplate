<?php
namespace Modules\Api\View\Components\ApiRole\Listing;

use Modules\Api\Models\ApiRole;
use Modules\Core\View\Components\Listing\Grid as CoreGrid;
class Grid extends CoreGrid
{
    public function __construct()
    {
        parent::__construct();
        $this->title('Manage Api Role');
    }

    public function prepareColumns()
    {
        if(canAccess('admin.apirole.massDelete')){
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

        $this->column('created_at', [
            'name'=>'created_at',
            'label'=>'Created Date',
            'sortable'=>true,
        ]);
        return $this;
    }

    public function prepareActions()
    {
        if(canAccess('admin.apirole.edit')){
            $this->action('edit' , [
                'id' => 'editBtn',
                'title' => 'Edit',
                'url' => 'admin.apirole.edit'
            ]);
        }
        if(canAccess('admin.apirole.delete')){
            $this->action('delete' , [
                'id' => 'deleteBtn',
                'title' => 'Delete',
                'url' => 'admin.apirole.delete'
            ]);
        }
        return $this;
    }

    public function prepareMassActions(){
        if(canAccess('admin.apirole.massDelete')){
            parent::prepareMassActions();
            $this->massAction('delete', [
                'value'=>'mass_delete',
                'label' =>'Delete Selected',
                'url' => 'admin.apirole.massDelete',
            ]);
        }

        if (canAccess('admin.apirole.export')) {
            $this->massAction('export', [
                'value' => 'mass_export',
                'label' => 'Export',
                'url' => 'admin.apirole.export',
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
        $ApiRole = $this->model(ApiRole::class);
        $query = $ApiRole->query();
        if($this->sortColumn() && $this->sortDir()){
             $query->orderBy($this->sortColumn(), $this->sortDir());
        }
        $this->applyFilters($query);
        $this->pager($query);
        return $this;
    }

    public function prepareButtons()
    {
        if(canAccess('admin.apirole.add')){
            $this->button('add', [
                'route' =>urlx('admin.apirole.add',[],true),
                'label' => 'Add Api Role',
            ]);
        }
        return $this;
    }
}