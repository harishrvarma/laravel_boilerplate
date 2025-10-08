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
                'name'=>'cron_id',
                'label'=>'',
                'columnClassName'=>'\Modules\Core\View\Components\Listing\Grid\Columns\MassIds',
            ]);
        }

        $this->column('cron_id', [
            'name'=>'cron_id',
            'label'=>'ID',
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
        $this->column('class', [
            'name'=>'class',
            'label'=>'Class',
        ]);
        $this->column('method', [
            'name'=>'method',
            'label'=>'Method',
        ]);
        $this->column('frequency', [
            'name'=>'frequency',
            'label'=>'Frequency',
        ]);
        $this->column('last_run_at', [
            'name'=>'last_run_at',
            'label'=>'Last Run At',
        ]);
        $this->column('is_active', [
            'name'=>'is_active',
            'label'=>'Active',
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
        if (canAccess('admin.cron.run')) {
            $this->action('run', [
                'id' => 'runBtn',
                'title' => 'Run Now',
                'url' => 'admin.cron.run',
                'class' => 'btn btn-success',
            ]);
        }
        return $this;
    }

    public function prepareFilters()
    {
        $this->filter('name', ['type' => 'text', 'name' => 'name']);
        $this->filter('command', ['type' => 'text', 'name' => 'command']);
        $this->filter('expression', ['type' => 'text', 'name' => 'expression']);
        $this->filter('class', ['type' => 'text', 'name' => 'class']);
        $this->filter('frequency', ['type' => 'text', 'name' => 'frequency']);
        $this->filter('next_run_at', ['type' => 'date', 'name' => 'next_run_at']);
        $this->filter('last_run_at', ['type' => 'date', 'name' => 'last_run_at']);
        $this->filter('is_active', [
            'type' => 'select',
            'options' => [1 => 'Active', 0 => 'Inactive'],
            'name' => 'is_active'
        ]);
        $this->filter('created_at', ['type' => 'date', 'name' => 'created_at']);

        return $this;
    }

    public function prepareMassActions()
    {
        if(canAccess('admin.cron.massDelete')){
            parent::prepareMassActions();
            $this->massAction('delete', [
                'value'=>'mass_delete',
                'label' =>'Delete Selected',
                'url' => 'admin.cron.massDelete',
            ]);
        }
    }

    public function prepareButtons()
    {
        if(canAccess('admin.cron.add')){
            $this->button('add', [
                'route' =>urlx('admin.cron.add',[],true),
                'label' => 'Add Cron',
            ]);
        }
    }

    public function prepareCollection() 
    {
        $query = Cron::query();

        if($this->sortColumn() && $this->sortDir()){
             $query->orderBy($this->sortColumn(), $this->sortDir());
        }

        $this->applyFilters($query);
        $this->pager($query);

        $this->collection = $query->get();

        return $this;
    }

    public function demoMethod() {
        return 'hello';
    }
}
