<?php

namespace Modules\Events\View\Components\Listener\Listing\Edit;

use Modules\Core\View\Components\Listing\Edit\Tabs as CoreTabs;
use Exception;

class Tabs extends CoreTabs
{
    public function __construct(){
        parent::__construct();
    }

    public function prepareTabs(){
        $this->tab('listener', [
            'key' => 'listener',
            'title' => 'Listener',
            'tabClassName'=>'\Modules\Events\View\Components\Listener\Listing\Edit\Tabs\General',
        ]);
        return $this;
    }
}
