<?php
namespace Modules\ApiService\View\Components\ApiResource\Listing;

use Modules\ApiService\Models\ApiResource;
use Modules\Core\View\Components\Listing\Grid as CoreGrid;
class Grid extends CoreGrid
{
    public function __construct()
    {
        parent::__construct();
        $this->title('Manage Api Resources');
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

        $this->column('code', [
            'name'=>'code',
            'label'=>'code',
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
            'url' => 'admin.apiresource.edit'
        ]);
        $this->action('delete' , [
            'id' => 'deleteBtn',
            'title' => 'Delete',
            'url' => 'admin.apiresource.delete'
        ]);
        return $this;
    }

    public function prepareMassActions(){
        parent::prepareMassActions();
        $this->massAction('delete', [
            'value'=>'mass_delete',
            'label' =>'Delete Selected',
            'url' => 'admin.apiresource.massDelete',
        ]);
        $this->massAction('export', [
            'value'=>'mass_export',
            'label' =>'Export Selected',
            'url' => 'admin.apiresource.massExport',
        ]);
    }

    public function prepareFilters()
    {
        $this->filter('code', [
            'type'  => 'text',
            'label' => 'Code',
        ]);

        $this->filter('created_at', [
            'type'  => 'date',
            'label' => 'Created Date',
        ]);

        return $this;
    }
    
    public function prepareCollection() 
    {
        $apiResource = $this->model(ApiResource::class);
        $query = $apiResource->query();
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
            'route' =>urlx('admin.apiresource.add',[],true),
            'label' => 'Add Api Resource',
        ]);
        return $this;
    }
}