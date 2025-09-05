<?php

namespace Modules\Admin\View\Components\Role\Listing\Edit\Tabs\Resource;

use Modules\Admin\View\Components\Role\Listing\Edit\Tabs\Resource;

class RenderResource extends Resource
{
    public $node;
    public $selected;
    protected $template = 'admin::components.role.listing.edit.tabs.resource.renderResource';

    public function node($node = null){
        if(!is_null($node)){
            $this->node = $node;
            return $this;
        }
        return $this->node;
    }
    public function selected($selected = null){
        if(!is_null($selected)){
            $this->selected = $selected;
            return $this;
        }
        return $this->selected;
    }

}