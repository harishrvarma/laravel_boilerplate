<?php

namespace Modules\Core\View\Components\Scaffold\Listing\Edit;

use Modules\Core\View\Components\Listing\Edit\Tabs as CoreTabs;
use Exception;

class Tabs extends CoreTabs
{
    public function __construct(){
        parent::__construct();
    }

    public function prepareTabs(){
        $this->tab('module', [
            'key' => 'module',
            'title' => 'Module',
            'tabClassName'=>'\Modules\Core\View\Components\Scaffold\Listing\Edit\Tabs\General',
        ]);
        return $this;
    }
}
