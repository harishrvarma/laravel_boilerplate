<?php

namespace Modules\Cron\View\Components\Cron\Listing\Grid\Columns;

use Modules\Core\View\Components\Listing\Grid\Columns\Renderer;

class Schedule extends Renderer
{
    protected $template = 'cron::components.listing.grid.columns.schedule';

    public function value($columnName)
    {
        $row = $this->row;

        // Only handle cron_id column
        if ($columnName === 'cron_id' && $row->cron_id) {
            // Fetch cron name via relation
            return $row->cron ? $row->cron->name : null;
        }

        return $row->$columnName ?? null;
    }
}
