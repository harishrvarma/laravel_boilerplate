<?php

namespace Modules\Settings\View\Components\Listing\Edit\Tabs;

use Modules\Core\View\Components\Listing\Edit\Form as CoreForm;

class Fields extends CoreForm
{
    public function __construct(){
        parent::__construct();
    }

    public function prepareFields()
    {
        // Key Name
        $this->field('key_name', [
            'id'    => 'key_name',
            'name'  => 'config[key_name]',
            'label' => 'Key Name',
            'type'  => 'text',
        ]);

        // Label
        $this->field('label', [
            'id'    => 'label',
            'name'  => 'config[label]',
            'label' => 'Label',
            'type'  => 'text',
        ]);

        // Input Type
        $this->field('input_type', [
            'id'      => 'input_type',
            'name'    => 'config[input_type]',
            'label'   => 'Input Type',
            'type'    => 'select',
            'options' => [
                'text'     => 'Text',
                'number'   => 'Number',
                'select'   => 'Select',
                'radio'    => 'Radio',
                'checkbox' => 'Checkbox',
                'textarea' => 'Textarea',
                'boolean'  => 'Boolean',
                'file'     => 'File',
                'date'     => 'Date',
            ],
        ]);

        // Is Required
        $this->field('is_required', [
            'id'    => 'is_required',
            'name'  => 'config[is_required]',
            'label' => 'Required',
            'type'  => 'checkbox',
            'value' => 1,
        ]);

        // Default Value
        $this->field('default_value', [
            'id'    => 'default_value',
            'name'  => 'config[default_value]',
            'label' => 'Default Value',
            'type'  => 'text',
        ]);

        return $this;
    }

}
