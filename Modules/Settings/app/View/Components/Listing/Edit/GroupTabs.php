<?php

namespace Modules\Settings\View\Components\Listing\Edit;
use Modules\Core\View\Components\Listing\Edit\Tabs as CoreTabs;
use Modules\Core\View\Components\Block;
use Modules\Settings\Models\ConfigGroup;
use Exception;

class GroupTabs extends CoreTabs
{   
    public function __construct(){
        parent::__construct();
    }

    public function prepareTabs(){
        $this->tab('group', [
            'key' => 'group',
            'title' => 'Add Group',
            'tabClassName'=>'\Modules\Settings\View\Components\Listing\Edit\Tabs\Group',
        ]);
        return $this;
    }
}