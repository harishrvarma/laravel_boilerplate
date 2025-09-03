<?php

namespace Modules\Core\View\Components\Listing\Edit;
use Modules\Core\View\Components\Block;

class Form extends Block
{
    protected array $fields = [];
    protected $template = 'core::listing.edit.form';

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
        'fieldClassName'=> '\Modules\Core\View\Components\Listing\Edit\Form\Field',
        'type'=>"text",
        'options'=>[],
    ];

    public function __construct(){
        parent::__construct();
        $this->prepareFields();
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
