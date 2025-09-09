<?php

namespace Modules\Api\View\Components\ApiRole\Listing\Edit;

use Modules\Core\View\Components\Listing\Edit\Tabs as CoreTabs;

class Tabs extends CoreTabs
{
    public function __construct(){
        parent::__construct();
    }

    public function prepareTabs(){
        $this->tab('apirole', [
            'key' => 'apirole',
            'title' => 'Api Role',
            'tabClassName'=>'\Modules\Api\View\Components\ApiRole\Listing\Edit\Tabs\General',
        ]);
        $this->tab('resource', [
            'key' => 'resource',
            'title' => 'Resource',
            'tabClassName'=>'\Modules\Api\View\Components\ApiRole\Listing\Edit\Tabs\Resource',
        ]);
        return $this;
    }
}
