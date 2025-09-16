<?php

namespace Modules\Translation\View\Components\Translation\Listing\Edit;

use Modules\Core\View\Components\Listing\Edit\Tabs as CoreTabs;
use Exception;

class Tabs extends CoreTabs
{
    public function __construct(){
        parent::__construct();
    }

    public function prepareTabs(){
        $this->tab('translation', [
            'key' => 'translation',
            'title' => 'Translation',
            'tabClassName'=>'Modules\Translation\View\Components\Translation\Listing\Edit\Tabs\General',
        ]);
        return $this;
    }
}
