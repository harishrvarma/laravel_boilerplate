<?php
namespace Modules\Core\View\Components\Eav\Html;
use Modules\Core\View\Components\Eav\Block;

class Head extends Block
{
    protected $styles = [];

    protected $scripts = [];

    protected $title = null;

    protected $template = 'eav::components.layout.html.head';

    public function styles($styles = null){
        if(is_array($styles)){
            $this->styles = $styles;
            return $this;
       }
       return $this->styles;
   }

   public function scripts($scripts = null){
        if(is_array($scripts)){
            $this->scripts = $scripts;
            return $this;
       }
       return $this->scripts;
   }

   public function title($title = null){
       if(!is_null($title)){
            $this->title = $title;
            return $this;
       }
       return $this->title;
   }
}