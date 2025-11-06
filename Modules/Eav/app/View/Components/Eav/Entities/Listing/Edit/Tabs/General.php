<?php

namespace Modules\Eav\View\Components\Eav\Entities\Listing\Edit\Tabs;

use Modules\Core\View\Components\Listing\Edit\Form as CoreForm;

class General extends CoreForm
{
    public function __construct()
    {
        parent::__construct();
    }

    public function prepareFields()
    {
        $this->field('entity_type_id', [
            'id' => 'entity_type_id',
            'name' => 'Types[entity_type_id]',
            'type' => 'hidden',
        ]);

        $this->field('code', [
            'id' => 'code',
            'name' => 'Types[code]',
            'label' => 'Code',
            'type' => 'text',
            'placeholder' => 'e.g. product, category',
            'required' => true,
        ]);

        $this->field('name', [
            'id' => 'name',
            'name' => 'Types[name]',
            'label' => 'Name',
            'type' => 'text',
            'placeholder' => 'Enter entity type name',
            'required' => true,
        ]);

        $this->field('model_class', [
            'id' => 'model_class',
            'name' => 'Types[model_class]',
            'label' => 'Model Class',
            'type' => 'text',
            'placeholder' => 'e.g. Modules\\Product\\Models\\Product',
        ]);

        return $this;
    }
}
