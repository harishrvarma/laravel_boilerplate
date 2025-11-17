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
            $fieldLangId = $attr->lang_type ? config_locale_id() : current_locale_id();
    
            $attributeValue = $this->entity?->values
                ->firstWhere(fn($v) => $v->attribute_id == $attr->attribute_id && $v->lang_id == $fieldLangId);
    
            $valueId    = $attributeValue?->value_id;
            $recordType = $valueId ? 'exist' : 'new';
    
            $translation = $attr->translations->firstWhere('lang_id', $fieldLangId);
            $label = $translation?->display_name ?? $attr->code;
    
            $options = [];
    
            if (in_array($attr->data_type, ['select', 'checkbox', 'radio'])) {
                $options = $attr->options()
                    ->where('is_active', 1)
                    ->orderBy('position')
                    ->get()
                    ->mapWithKeys(function ($opt) use ($langId) {
                        $t = $opt->translations->firstWhere('lang_id', $langId);
                        $label = $t?->display_name ?? 'Option ' . $opt->option_id;
                        return [$opt->option_id => $label];
                    })
                    ->toArray();
            }
    
            $this->field('attr_' . $attr->attribute_id, [
                'id'      => 'attr_' . $attr->attribute_id,
                'name'    => 'entity_data[' . $attr->attribute_id . '][' . $fieldLangId . '][' . $recordType . ']',
                'label'   => $label,
                'type'    => $attr->data_type,
                'value'   => $attributeValue?->value,
                'options' => $options,
            ]);
        }
    
        return $this;
    }
}


