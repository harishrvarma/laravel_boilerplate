<?php

namespace Modules\Api\View\Components\ApiRole\Listing\Edit\Tabs;

use Modules\Api\Models\ApiResource as ModelResource;
use Modules\Api\Models\ApiRoleResource as RoleResource;
use Modules\Core\View\Components\Block;

class Resource extends Block
{
    protected $template = 'api::components.apiRole.listing.edit.tabs.resource';

    protected $selected = [];

    public function __construct()
    {
        parent::__construct();
        $this->selected(request('id'));    
    }
    public function resources(){
        return ModelResource::orderBy('id')->get();
    }

    public function buildTree($resources, $parentId = null) {
        return $resources
            ->where('parent_id', $parentId)
            ->map(function ($resource) use ($resources) {
                $resource->children = $this->buildTree($resources, $resource->id);
                return $resource;
            });
    }

    public function selected($id = null){
        if (!is_null($id)) {
            $this->selected = RoleResource::where('role_id', $id)
                ->pluck('resource_id')
                ->toArray();
            return $this;
        }
        return $this->selected;
    }

    public function getResourceBlock($node,$selected){
        return $this->block('\Modules\Api\View\Components\ApiRole\Listing\Edit\Tabs\Resource\RenderResource')
            ->node($node)
            ->selected($selected);
    }
}
