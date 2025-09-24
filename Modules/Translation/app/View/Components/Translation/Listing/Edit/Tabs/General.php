<?php

namespace Modules\translation\View\Components\translation\Listing\Edit\Tabs;

use Modules\Core\View\Components\Listing\Edit\Form as CoreForm;
use Illuminate\Support\Collection;
use Nwidart\Modules\Facades\Module;
use Modules\Translation\Models\TranslationLocale;

class General extends CoreForm
{
    public function __construct(){
        parent::__construct();
    }
    
    public function prepareFields()
    {
        $this->field('module', [
            'id'    => 'module',
            'name'  => 'translation[module][]',
            'label' => 'Module',
            'type'  => 'select',
            'multiselect'  => true,
            'options' => collect(Module::all())
                ->mapWithKeys(function ($mod) {
                    return [$mod->getName() => $mod->getName()];
                })
                ->toArray(),
            'required' => true,
            'relation' => 'module',
        ]);
    
        $this->field('locale_id', [
            'id'    => 'locale_id',
            'name'  => 'translation[locale_id]',
            'label' => 'Locale',
            'type'  => 'select',
            'options' => TranslationLocale::pluck('label', 'id')->toArray(),
            'required' => true,
        ]);
        
    
        $this->field('group', [
            'id'    => 'group',
            'name'  => 'translation[group]',
            'label' => 'Group',
            'type'  => 'text',
            'required' => true,
        ]);
    
        $this->field('key', [
            'id'    => 'key',
            'name'  => 'translation[key]',
            'label' => 'Key',
            'type'  => 'text',
            'required' => true,
        ]);
    
        $this->field('value', [
            'id'    => 'value',
            'name'  => 'translation[value]',
            'label' => 'Value',
            'type'  => 'textarea',
            'rows'  => 3,
            'required' => true,
        ]);
    
        return $this;
    }
    
}
