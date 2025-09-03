<?php
namespace Modules\Core\View\Components;

use Exception;
use Modules\Core\View\Components\Block;

class Listing extends Block
{
    protected $template = "core::listing";

    protected $gridClassName = "\Modules\Core\View\Components\Listing\Grid"; 

    protected $buttons = [];

    protected $buttonFormat = [
        'label'=>null,
        'route'=>null,
    ];

    public function __construct(){
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

    public function getGridBlock() {
        return $this->block($this->gridClassName);
    }
}