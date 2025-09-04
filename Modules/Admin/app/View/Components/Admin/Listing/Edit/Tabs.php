<?php

namespace Modules\Admin\View\Components\Admin\Listing\Edit;

use Modules\Core\View\Components\Listing\Edit\Tabs as CoreTabs;
use Exception;

class Tabs extends CoreTabs
{
    public function __construct(){
        parent::__construct();
    }

    public function prepareTabs(){
        $this->tab('admin', [
            'key' => 'admin',
            'title' => 'Admin',
            'tabClassName'=>'\Modules\Admin\View\Components\Admin\Listing\Edit\Tabs\General',
        ]);
        return $this;
    }
}
