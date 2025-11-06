<?php

namespace Modules\Eav\View\Components\Eav\Attributes\Group\Listing\Edit;

use Modules\Core\View\Components\Listing\Edit\Tabs as CoreTabs;
use Modules\Translation\Models\TranslationLocale;

class Tabs extends CoreTabs
{
    protected $template = 'eav::group.listing.edit.tabs';
    public $languages;
    public function __construct()
    {
        parent::__construct();
    }

    public function prepareTabs()
    {
        $this->tab('Attribute', [
            'key'   => 'Attribute',
            'title' => 'Attribute',
        ]);

        return $this;
    }

    public function getLanguages()
    {
        if (!$this->languages) {
            $this->languages = TranslationLocale::select('id', 'code', 'label')->get();
        }
        return $this->languages;
    }
    
}
