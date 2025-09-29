<?php

namespace Modules\Menu\View\Components\Menu\Listing\Edit;

use Modules\Core\View\Components\Listing\Edit\Tabs as CoreTabs;
use Exception;

class Tabs extends CoreTabs
{
    public function __construct(){
        parent::__construct();
    }

    public function prepareTabs(){
        $this->tab('menu', [
            'key' => 'menu',
            'title' => 'Menu',
            'tabClassName'=>'\Modules\Menu\View\Components\Menu\Listing\Edit\Tabs\General',
        ]);
        $this->tab('resource', [
            'key' => 'resource',
            'title' => 'Resource',
            'tabClassName'=>'\Modules\Menu\View\Components\Menu\Listing\Edit\Tabs\Resource',
        ]);
        return $this;
    }
}
