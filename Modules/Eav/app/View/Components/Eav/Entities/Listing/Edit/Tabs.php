<?php

namespace Modules\Eav\View\Components\Eav\Entities\Listing\Edit;

use Modules\Core\View\Components\Listing\Edit\Tabs as CoreTabs;

class Tabs extends CoreTabs
{
    public function __construct()
    {
        parent::__construct();
    }

    public function prepareTabs()
    {
        $this->tab('eav_entities', [
            'key'   => 'eav_entities',
            'title' => 'Entites',
            'tabClassName' => '\Modules\Eav\View\Components\Eav\Entities\Listing\Edit\Tabs\General',
        ]);

        return $this;
    }
}
