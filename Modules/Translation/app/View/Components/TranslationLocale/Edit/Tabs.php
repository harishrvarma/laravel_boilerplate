<?php

namespace Modules\Translation\View\Components\TranslationLocale\Edit;

use Modules\Core\View\Components\Listing\Edit\Tabs as CoreTabs;
use Exception;

class Tabs extends CoreTabs
{
    public function __construct(){
        parent::__construct();
    }

    public function prepareTabs(){
        $this->tab('translationLocale', [
            'key' => 'translationLocale',
            'title' => 'Language',
            'tabClassName'=>'Modules\Translation\View\Components\TranslationLocale\Edit\Tabs\General',
        ]);
        return $this;
    }
}
