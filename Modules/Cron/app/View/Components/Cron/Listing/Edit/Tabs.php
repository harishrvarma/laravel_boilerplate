<?php

namespace Modules\Cron\View\Components\Cron\Listing\Edit;

use Modules\Core\View\Components\Listing\Edit\Tabs as CoreTabs;
use Exception;

class Tabs extends CoreTabs
{
    public function __construct(){
        parent::__construct();
    }

    public function prepareTabs(){
        $this->tab('cron', [
            'key' => 'cron',
            'title' => 'Cron',
            'tabClassName'=>'\Modules\Cron\View\Components\Cron\Listing\Edit\Tabs\General',
        ]);
        $this->tab('logs', [
            'key' => 'logs',
            'title' => 'Logs',
            'tabClassName'=>'\Modules\Cron\View\Components\Cron\Listing\Edit\Tabs\ScheduleGrid',
        ]);
        return $this;
    }
}
