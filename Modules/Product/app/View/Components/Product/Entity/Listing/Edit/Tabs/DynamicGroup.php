<?php

namespace Modules\Product\View\Components\Product\Entity\Listing\Edit\Tabs;

use Modules\Core\View\Components\Eav\Listing\Edit\Form as CoreForm;

class DynamicGroup extends CoreForm
{
    protected $group;
    protected $entity;

    public function __construct(array $tabData = [])
    {
        parent::__construct($tabData);
        $this->group  = $this->tabData['group'] ?? null;
        $this->entity = $this->tabData['entity'] ?? null;
        $this->prepareFields();
    }

    public function prepareFields()
    {
        if (!$this->group) return $this;
    
        foreach ($this->group->attributes as $attr) {
            $value = $this->entity?->values
                        ->firstWhere('attribute_id', $attr->attribute_id)?->value;
    
            $label = $attr->translation?->display_name ?? $attr->code;
    
            $this->field('attr_' . $attr->attribute_id, [
                'id'    => 'attr_' . $attr->attribute_id,
                'name'  => 'attributes[' . $attr->attribute_id . ']',
                'label' => $label,
                'type'  => $attr->data_type,
                'value' => $value,
            ]);
        }
    
        return $this;
    }
    
}


