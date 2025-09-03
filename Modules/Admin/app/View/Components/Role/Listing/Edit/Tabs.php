<?php

namespace Modules\Admin\View\Components\Role\Listing\Edit;

use Modules\Core\View\Components\Listing\Edit\Tabs as CoreTabs;
use Exception;

class Tabs extends CoreTabs
{
    public function __construct(){
        parent::__construct();
    }

    public function prepareTabs(){
        $this->tab('role', [
            'key' => 'role',
            'title' => 'Role',
            'tabClassName'=>'\Modules\Admin\View\Components\Role\Listing\Edit\Tabs\General',
        ]);
        return $this;
    }
}
