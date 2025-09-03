<?php

namespace Modules\Settings\View\Components\Listing\Edit;
use Modules\Core\View\Components\Listing\Edit\Tabs as CoreTabs;
use Modules\Core\View\Components\Block;
use Modules\Settings\Models\ConfigGroup;
use Exception;

class Tabs extends CoreTabs
{   
    protected $template = 'settings::listing.config.tabs';
     
    public function __construct(){
        parent::__construct();
    }

    public function prepareTabs()
    {
        $groups = ConfigGroup::orderBy('id', 'asc')->get();
        foreach ($groups as $group) {
            $this->tab($group->id, [
                'key'       => $group->id,
                'title'     => $group->name,
                'tabClassName'=>'\Modules\Settings\View\Components\Listing\Edit\Tabs\General',
            ]);
        }
        return $this;
    }
}