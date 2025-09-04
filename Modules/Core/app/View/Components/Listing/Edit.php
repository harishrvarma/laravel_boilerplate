<?php

namespace Modules\Core\View\Components\Listing;

use Exception;
use Modules\Core\View\Components\Block;

class Edit extends Block
{

    protected array $buttons = [];

    protected $buttonFormat = [];

    protected $title = null;

    protected $template = 'core::listing.edit';

    protected $tabsClassName = '\Modules\Core\View\Components\Listing\Edit\Tabs';

    public function __construct()
    {
        parent::__construct();
        $this->prepareButtons();
    }

    public function prepareButtons(){
        return $this;
    }


    public function button(string $key, array $value = null, bool $reset = false)
    {
        if(is_null($key)){
            return $this;
        }

        if($reset == true){
            if(array_key_exists($key,$this->buttons)){
                unset($this->buttons[$key]);
            }
            return $this;
        }

        if(!is_null($value)){
            $this->buttons[$key] = array_merge($this->buttonFormat, $value);
            return $this;
        }

        if(array_key_exists($key,$this->buttons)){
            return $this->buttons[$key];
        }
        return null;
    }

    public function buttons(array $buttons = null, bool $reset = false)
    {
        if($reset == true){
            $this->buttons = [];
            return $this;
        }

        if(!is_null($buttons)){
            foreach($buttons as $key => $button){
                if(is_array($button)){
                    $this->button($key, $button);
                }
            }
            return $this;
        }
        return $this->buttons;
    }

    public function saveUrl(){
        // if($this->row()->id){
            // return  route('admin.admin.save',['id'=>$this->row()->id]);
        // }
        // return  route('admin.admin.save');
    }

    public function getTabsBlock() {
        return $this->block($this->tabsClassName)->row($this->row());
    }

    public function title(string $title = null){
        if(!is_null($title)){
            $this->title = $title;
            return $this;
        }
        return $this->title;
    }
}