<?php

namespace Modules\ApiService\View\Components\ApiUser\Listing\Edit;

use Modules\Core\View\Components\Listing\Edit\Tabs as CoreTabs;

class Tabs extends CoreTabs
{
    public function __construct(){
        parent::__construct();
    }

    public function prepareTabs(){
        $this->tab('apiuser', [
            'key' => 'apiuser',
            'title' => 'Api User',
            'tabClassName'=>'\Modules\ApiService\View\Components\ApiUser\Listing\Edit\Tabs\General',
        ]);
        return $this;
    }
}
