<?php
namespace Modules\Core\View\Components;
use Modules\Core\View\Components\Block;
use Modules\Core\View\Components\Html\Content;
use Modules\Core\View\Components\Html\Header;
use Modules\Core\View\Components\Html\Footer;
use Modules\Core\View\Components\Html\Head;

class Layout extends Block
{
    protected $template = 'core::components.layout.layout';

    public function __construct(){
        parent::__construct();
    }

    public function init()  {
        $this->child('header',$this->block(Header::class));
        $this->child('content',$this->block(Content::class));
        $this->child('footer',$this->block(Footer::class));
        $this->child('head',$this->block(Head::class));
        return $this;
    }

    public function title(string $title = null) {
        if(!empty($title)){
            $this->child('head')->title($title);
            return $this;
        }
        return $this->child('head')->title();
    }
}
