<?php

namespace Modules\Settings\View\Components\Listing\Edit;
use Modules\Core\View\Components\Listing\Edit\Tabs as CoreTabs;
use Modules\Core\View\Components\Block;
use Modules\Settings\Models\ConfigGroup;
use Exception;

class FieldTabs extends CoreTabs
{   
    public function __construct(){
        parent::__construct();
    }

    public function prepareTabs(){
        $this->tab('fields', [
            'key' => 'fields',
            'title' => 'Add Fields',
            'tabClassName'=>'\Modules\Settings\View\Components\Listing\Edit\Tabs\Fields',
        ]);
        return $this;
    }
}