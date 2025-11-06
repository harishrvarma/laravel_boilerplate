<?php

namespace Modules\Core\View\Components\Eav\Listing\Edit;
use Modules\Core\View\Components\Eav\Block;

class Form extends Block
{
    protected array $fields = [];
    protected $template = 'core::listing.edit.form';

    protected array $buttons = [];
    protected $buttonFormat = [];
    protected array $tabData = [];
    
    protected $fieldFormat = [
        'id'=>null,
        'label'=>null,
        'name'=>null,
        'class'=>null,
        'value'=>null,
        'placeholder'=>null,
        'required'=>null,
        'multiple'=>null,
        'accept'=>null,
        'step'=>null,
        'min'=>null,
        'max'=>null,
        'fieldClassName'=> '\Modules\Core\View\Components\Eav\Listing\Edit\Form\Field',
        'type'=>"text",
        'options'=>[],
    ];

    public function __construct(array $tabData = []){
        parent::__construct();
        $this->tabData = $tabData;
        $this->prepareButtons();
        // $this->prepareFields();
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

    /* ---------- FIELD METHODS ---------- */
    public function prepareFields(){
        return $this;
    }

    public function field(string $key, array $value = null, bool $reset = false)
    {
        if(is_null($key)){
            return $this;
        }

        if($reset == true){
            if(array_key_exists($key,$this->fields)){
                unset($this->fields[$key]);
            }
            return $this;
        }

        if(!is_null($value)){
            $this->fields[$key] = array_merge($this->fieldFormat, $value);
            return $this;
        }

        if(array_key_exists($key,$this->fields)){
            return $this->fields[$key];
        }
        return null;
    }

    public function fields(array $fields = null, bool $reset = false)
    {
        if($reset == true){
            $this->fields = [];
            return $this;
        }

        if(!is_null($fields)){
            foreach($fields as $key => $field){
                if(is_array($field)){
                    $this->field($key, $field);
                }
            }
            return $this;
        }
        return $this->fields;
    }
    
    public function getFieldBlock($field) {
        return $this->block($field['fieldClassName'])->row($this->row())->field($field);
    }
}
