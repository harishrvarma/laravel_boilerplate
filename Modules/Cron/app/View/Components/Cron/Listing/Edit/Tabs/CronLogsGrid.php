<?php
namespace Modules\Cron\View\Components\Cron\Listing\Edit\Tabs;

use Modules\Cron\Models\CronSchedule;
use Modules\Core\View\Components\Listing\Grid as CoreGrid;
use Modules\Cron\Models\CronLog;

class CronLogsGrid extends CoreGrid
{
    public function prepareColumns()
    {
        $this->column('id', [
            'name'=>'id',
            'label'=>'Id',
        ]);
        $this->column('name', [
            'name'=>'name',
            'label'=>'Name',
            'columnClassName'=>"\Modules\Cron\View\Components\Cron\Listing\Grid\Columns\Schedule",
        ]);

        $this->column('message', [
            'name'=>'message',
            'label'=>'Message',
        ]);
        $this->column('started_at', [
            'name'=>'started_at',
            'label'=>'Started At',
        ]);
        $this->column('finished_at', [
            'name'=>'finished_at',
            'label'=>'Finished At',
        ]);
        $this->column('status', [
            'name'=>'status',
            'label'=>'status',
        ]);

        $this->column('created_at', [
            'name'=>'created_at',
            'label'=>'Created Date',
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

    public function prepareCollection() 
    {
        $admin = $this->model(CronLog::class);
        $query = $admin->query();
        $query->where('cron_schedule_id',request('id'));
        if($this->sortColumn() && $this->sortDir()){
             $query->orderBy($this->sortColumn(), $this->sortDir());
        }
        $this->applyFilters($query);
        $this->pager($query);
        return $this;
    }
}