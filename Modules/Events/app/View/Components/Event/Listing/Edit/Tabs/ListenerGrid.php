<?php
namespace Modules\Events\View\Components\Event\Listing\Edit\Tabs;

use Modules\Events\Models\Events;
use Modules\Core\View\Components\Listing\Grid as CoreGrid;
use Modules\Events\Models\Listener;

class ListenerGrid extends CoreGrid
{

    public function __construct()
    {
        parent::__construct();
        $this->allowedPagination(false);
    }
    public function prepareColumns()
    {
        $this->column('id', [
            'name'=>'id',
            'label'=>'Id',
        ]);

        $this->column('name', [
            'name'=>'name',
            'label'=>'Name',
        ]);

        $this->column('created_at', [
            'name'=>'created_at',
            'label'=>'Created Date',
        ]);
        return $this;
    }

    public function prepareActions()
    {
        if(canAccess('admin.listener.edit')){    
            $this->action('edit' , [
                'id' => 'editBtn',
                'title' => 'Edit',
                'url' => 'admin.listener.edit'
            ]);
        }
        if(canAccess('admin.listener.delete')){
            $this->action('delete' , [
                'id' => 'deleteBtn',
                'title' => 'Delete',
                'url' => 'admin.listener.delete'
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
    
    public function prepareCollection() 
    {
        $listener = $this->model(Listener::class);
        $query = $listener->query();
        $query->where('event_id',request('id'));
        if($this->sortColumn() && $this->sortDir()){
             $query->orderBy($this->sortColumn(), $this->sortDir());
        }
        $this->applyFilters($query);
        $this->pager($query);
        return $this;
    }
}