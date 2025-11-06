<?php
 
namespace Modules\Core\View\Components\Eav\Listing\Grid\Columns;
use Modules\Core\View\Components\Eav\Block;
 
class Renderer extends Block
{

    protected $column = null;

    protected $template = 'core::listing.grid.columns.renderer';

    public function __construct(){
        parent::__construct();
    }

    public function column(array $column = null)
    {
        if(!is_null($column)){
            $this->column = $column;
            return $this;
        }
        return $this->column;
    }

    public function value($columnName){
         if(is_null($columnName)){
            return null;
        }
        if(isset($this->row->$columnName)){
            return $this->row->$columnName;
        }
        return null;
    }

}