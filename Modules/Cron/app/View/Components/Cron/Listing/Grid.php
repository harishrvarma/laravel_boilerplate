<?php
namespace Modules\Cron\View\Components\Cron\Listing;

use Modules\Core\View\Components\Listing\Grid as CoreGrid;
use Modules\Cron\Models\Cron;

class Grid extends CoreGrid
{

    public function __construct()
    {
        parent::__construct();
        $this->title('Manage Cron');
    }

    public function prepareColumns()
    {
        if(canAccess('admin.cron.massDelete')){
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
        $this->column('expression', [
            'name'=>'expression',
            'label'=>'Expression',
        ]);
        $this->column('command', [
            'name'=>'command',
            'label'=>'Command',
        ]);
        $this->column('last_run_at', [
            'name'=>'last_run_at',
            'label'=>'Last Run At',
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
        if(canAccess('admin.cron.edit')){
            $this->action('edit' , [
                'id' => 'editBtn',
                'title' => 'Edit',
                'url' => 'admin.cron.edit'
            ]);
        }
        if(canAccess('admin.cron.delete')){
            $this->action('delete' , [
                'id' => 'deleteBtn',
                'title' => 'Delete',
                'url' => 'admin.cron.delete'
            ]);
        }
        return $this;
    }

    public function prepareFilters()
    {
        $this->filter('name', [
            'type'  => 'text',
            'name' => 'name',
        ]);

        $this->filter('command', [
            'type'  => 'text',
            'name' => 'command',
        ]);

        $this->filter('expression', [
            'type'  => 'text',
            'name' => 'expression',
        ]);

        $this->filter('last_run_at', [
            'type'  => 'date',
            'name' => 'last_run_at',
        ]);

        $this->filter('created_at', [
            'type'  => 'date',
            'name' => 'created_at',
        ]);

        return $this;
    }

    public function prepareMassActions(){
        if(canAccess('admin.cron.massDelete')){
            parent::prepareMassActions();
            $this->massAction('delete', [
                'value'=>'mass_delete',
                'label' =>'Delete Selected',
                'url' => 'admin.cron.massDelete',
            ]);
        }
    }

    public function prepareButtons(){
        if(canAccess('admin.cron.add')){
            $this->button('add', [
                'route' =>urlx('admin.cron.add',[],true),
                'label' => 'Add Cron',
            ]);
        }
    }
    
    public function prepareCollection() 
    {
        $admin = $this->model(Cron::class);
        $query = $admin->query();
        if($this->sortColumn() && $this->sortDir()){
             $query->orderBy($this->sortColumn(), $this->sortDir());
        }
        $this->applyFilters($query);
        $this->pager($query);
        return $this;
    }

}