<?php
 
namespace Modules\Core\View\Components\Listing\Grid;

use Modules\Core\View\Components\Block;
 
class Filter extends Block
{

    protected $template = 'core::listing.grid.filter';
    protected $filter = [];
    protected $column = [];
    public function __construct()
    {
        parent::__construct();
    }

    public function filter(array $filter = null)
    {
        if($filter){
            $this->filter = $filter;
            return $this;
        }
        return $this->filter;
    }

    public function column(array $column = null)
    {
        if($column){
            $this->column = $column;
            $this->manageTemplate();
            return $this;
        }
        return $this->column;
    }
    
    public function manageTemplate()
    {
        if(!empty($this->filter['template'])){
            $this->template = $this->filter['template'];
            return $this;
        }
        if(!empty($this->filter['type'])){
            $this->template .= ".".$this->filter['type'];
            return $this;
        }
    }
}
