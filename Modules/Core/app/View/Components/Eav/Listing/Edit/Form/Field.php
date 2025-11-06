<?php

namespace Modules\Core\View\Components\Eav\Listing\Edit\Form;

use Modules\Core\View\Components\Eav\Block;

class Field extends Block
{
    protected $template = "core::listing.edit.form.field";

    function manageTemplate() {
        if(!empty($this->field['type'])){
            $this->template .= ".". $this->field['type'];
        }
        else{
            $this->template .= ".text";
        }
        return $this;
    }


    public function field(array $field = null)
    {
        if(!is_null($field)){
            $this->field = $field;
            $this->manageTemplate();
            return $this;
        }
        return $this->field;
    }
}
