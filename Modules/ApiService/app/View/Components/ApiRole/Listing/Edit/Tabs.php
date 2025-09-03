<?php

namespace Modules\ApiService\View\Components\ApiRole\Listing\Edit;

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
            'tabClassName'=>'\Modules\ApiService\View\Components\ApiRole\Listing\Edit\Tabs\General',
        ]);
        return $this;
    }
}
