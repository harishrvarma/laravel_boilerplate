<?php
namespace Modules\Cron\View\Components\Cron\Listing\Edit\Tabs;

use Modules\Core\View\Components\Listing\Grid as CoreGrid;
use Modules\Cron\Models\Cron\Schedule;

class ScheduleGrid extends CoreGrid
{
    public function prepareColumns()
    {
        $this->column('schedule_id', [
            'name' => 'schedule_id',
            'label' => 'ID',
        ]);

        $this->column('cron_id', [
            'name' => 'cron_id',
            'label' => 'Cron',
            'columnClassName' => "\Modules\Cron\View\Components\Cron\Listing\Grid\Columns\Schedule",
        ]);

        $this->column('log', [
            'name' => 'log',
            'label' => 'Log',
        ]);

        $this->column('scheduled_for', [
            'name' => 'scheduled_for',
            'label' => 'Scheduled For',
        ]);

        $this->column('started_at', [
            'name' => 'started_at',
            'label' => 'Started At',
        ]);

        $this->column('finished_at', [
            'name' => 'finished_at',
            'label' => 'Finished At',
        ]);

        $this->column('status', [
            'name' => 'status',
            'label' => 'Status',
        ]);

        $this->column('created_at', [
            'name' => 'created_at',
            'label' => 'Created Date',
        ]);

        return $this;
    }

    public function prepareFilters()
    {
        $this->filter('status', [
            'type' => 'select',
            'options' => [
                0 => 'Pending',
                1 => 'Success',
                2 => 'Failure',
            ],
            'name' => 'status'
        ]);

        $this->filter('started_at', [
            'type' => 'date',
            'name' => 'started_at'
        ]);

        $this->filter('finished_at', [
            'type' => 'date',
            'name' => 'finished_at'
        ]);

        return $this;
    }

    public function prepareCollection()
    {
        $query = Schedule::query();

        if (request('id')) {
            $query->where('cron_id', request('id'));
        }

        if ($this->sortColumn() && $this->sortDir()) {
            $query->orderBy($this->sortColumn(), $this->sortDir());
        }

        $this->applyFilters($query);
        $this->pager($query);

        return $this;
    }
}
