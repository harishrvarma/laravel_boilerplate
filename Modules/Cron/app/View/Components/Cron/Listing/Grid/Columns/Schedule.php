<?php
 
namespace Modules\Cron\View\Components\Cron\Listing\Grid\Columns;
 
use Modules\Core\View\Components\Listing\Grid\Columns\Renderer;

class Schedule extends Renderer
{
    protected $template = 'cron::components.listing.grid.columns.schedule';


    public function value($columnName){
        if(isset($this->row->schedule->$columnName)){
            return $this->row->schedule->$columnName;
        }
        return null;
   }
}