<?php
namespace Modules\Events\View\Components\Event\Listing;

use Modules\Events\Models\Events;
use Modules\Core\View\Components\Listing\Grid as CoreGrid;
class Grid extends CoreGrid
{
    public function __construct()
    {
        parent::__construct();
        $this->title('Manage Events');
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
        $this->column('name', [
            'name'=>'name',
            'label'=>'Name',
            'sortable'=>true,
        ]);
        $this->column('code', [
            'name'=>'code',
            'label'=>'Code',
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
            'url' => 'admin.event.edit'
        ]);

        $this->action('delete' , [
            'id' => 'deleteBtn',
            'title' => 'Delete',
            'url' => 'admin.event.delete'
        ]);
        return $this;
    }

    public function prepareFilters()
    {
        // $this->filter('first_name', [
        //     'type'  => 'text',
        //     'label' => 'First Name',
        // ]);

        return $this;
    }

    public function prepareMassActions(){
        parent::prepareMassActions();
        $this->massAction('delete', [
            'value'=>'mass_delete',
            'label' =>'Delete Selected',
            'url' => 'admin.event.massDelete',
        ]);
        $this->massAction('export', [
            'value'=>'mass_export',
            'label' =>'Export Selected',
            'url' => 'admin.event.massExport',
        ]);
    }
    
    public function prepareCollection() 
    {
        $admin = $this->model(Events::class);
        $query = $admin->query();
        if($this->sortColumn() && $this->sortDir()){
             $query->orderBy($this->sortColumn(), $this->sortDir());
        }
        $this->applyFilters($query);
        $this->pager($query);
        return $this;
    }
}