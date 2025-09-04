<?php
namespace Modules\Core\View\Components;
use Modules\Core\View\Components\Block;
use Modules\Core\View\Components\Html\Content;
use Modules\Core\View\Components\Html\Header;
use Modules\Core\View\Components\Html\Footer;

class Layout extends Block
{
    protected $template = 'core::components.layout.layout';

    protected $styles = [];

    protected $scripts = [];

    protected $title = null;

    public function __construct(){
        $this->child('header',$this->block(Header::class));
        $this->child('content',$this->block(Content::class));
        $this->child('footer',$this->block(Footer::class));
    }

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
