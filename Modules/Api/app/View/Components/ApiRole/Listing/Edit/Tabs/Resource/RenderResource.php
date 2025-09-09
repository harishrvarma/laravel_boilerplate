<?php

namespace Modules\Api\View\Components\ApiRole\Listing\Edit\Tabs\Resource;

use Modules\Api\View\Components\ApiRole\Listing\Edit\Tabs\Resource;

class RenderResource extends Resource
{
    public $node;
    public $selected;
    protected $template = 'api::components.apiRole.listing.edit.tabs.resource.renderResource';

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