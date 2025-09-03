<?php

namespace Modules\Events\View\Components\Event\Listing\Edit;

use Modules\Core\View\Components\Listing\Edit\Tabs as CoreTabs;
use Exception;

class Tabs extends CoreTabs
{
    public function __construct(){
        parent::__construct();
    }

    public function prepareTabs(){
        $this->tab('event', [
            'key' => 'event',
            'title' => 'Event',
            'tabClassName'=>'\Modules\Events\View\Components\Event\Listing\Edit\Tabs\General',
        ]);
        if(request('id')){
            $this->tab('listener', [
                'key' => 'listener',
                'title' => 'Listener',
                'tabClassName'=>'\Modules\Events\View\Components\Event\Listing\Edit\Tabs\ListenerGrid',
            ]);
        }
        return $this;
    }
}
