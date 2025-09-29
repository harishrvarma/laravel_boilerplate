<?php

namespace Modules\Menu\View\Components\Menu\Listing\Edit;
use Modules\Menu\View\Components\Menu\Listing\Edit\Tabs\Tree;

use Modules\Core\View\Components\Listing\Edit\Tabs as CoreTabs;

class TreeTabs extends CoreTabs
{
    public function __construct(){
        parent::__construct();
    }

    public function prepareTabs(){
        $this->tab('tree', [
            'key' => 'tree',
            'title' => 'Tree',
            'tabClassName'=>'\Modules\Menu\View\Components\Menu\Listing\Edit\Tabs\Tree',
        ]);
        return $this;
    }
}
