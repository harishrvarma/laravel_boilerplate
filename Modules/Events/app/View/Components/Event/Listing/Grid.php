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
        if(canAccess('admin.event.massDelete')){
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
        if(canAccess('admin.event.edit')){
            $this->action('edit' , [
                'id' => 'editBtn',
                'title' => 'Edit',
                'url' => 'admin.event.edit'
            ]);
        }
        if(canAccess('admin.event.delete')){
            $this->action('delete' , [
                'id' => 'deleteBtn',
                'title' => 'Delete',
                'url' => 'admin.event.delete'
            ]);
        }
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
        if(canAccess('admin.event.massDelete')){
            parent::prepareMassActions();
            $this->massAction('delete', [
                'value'=>'mass_delete',
                'label' =>'Delete Selected',
                'url' => 'admin.event.massDelete',
            ]);
        }
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

    public function prepareButtons(){
        if(canAccess('admin.event.add')){
            $this->button('add', [
                'route' =>route('admin.event.add'),
                'label' => 'Add Event',
            ]);
        }
        return $this;
    }
}