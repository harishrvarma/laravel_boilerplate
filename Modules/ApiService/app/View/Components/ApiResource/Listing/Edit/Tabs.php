<?php

namespace Modules\ApiService\View\Components\ApiResource\Listing\Edit;

use Modules\Core\View\Components\Listing\Edit\Tabs as CoreTabs;

class Tabs extends CoreTabs
{
    public function __construct(){
        parent::__construct();
    }

    public function prepareTabs(){
        $this->tab('apiresource', [
            'key' => 'apiresource',
            'title' => 'Api Resource',
            'tabClassName'=>'\Modules\ApiService\View\Components\ApiResource\Listing\Edit\Tabs\General',
        ]);
        return $this;
    }
}
