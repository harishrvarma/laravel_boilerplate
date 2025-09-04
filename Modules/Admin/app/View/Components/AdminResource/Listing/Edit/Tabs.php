<?php

namespace Modules\Admin\View\Components\AdminResource\Listing\Edit;

use Modules\Core\View\Components\Listing\Edit\Tabs as CoreTabs;
use Exception;

class Tabs extends CoreTabs
{
    public function __construct(){
        parent::__construct();
    }

    public function prepareTabs(){
        $this->tab('resource', [
            'key' => 'resource',
            'title' => 'Resource',
            'tabClassName'=>'\Modules\Admin\View\Components\AdminResource\Listing\Edit\Tabs\General',
        ]);
        return $this;
    }
}
