<?php

namespace Modules\translation\View\Components\translationLocale\Edit\Tabs;

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
        $this->field('code', [
            'id'    => 'code',
            'name'  => 'translationLocale[code]',
            'label' => 'Code',
            'type'  => 'text',
            'required' => true,
        ]);
    
        $this->field('label', [
            'id'    => 'label',
            'name'  => 'translationLocale[label]',
            'label' => 'Label',
            'type'  => 'text',
            'required' => true,
        ]);
    
        return $this;
    }
    
}
