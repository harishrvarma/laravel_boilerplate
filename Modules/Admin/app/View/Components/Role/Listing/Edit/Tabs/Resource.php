<?php

namespace Modules\Admin\View\Components\Role\Listing\Edit\Tabs;

use Modules\Admin\Models\Resource as ModelResource;
use Modules\Admin\Models\RoleResource;
use Modules\Core\View\Components\Block;

class Resource extends Block
{
    protected $template = 'admin::components.role.listing.edit.tabs.resource';

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
        return $this->block('\Modules\Admin\View\Components\Role\Listing\Edit\Tabs\Resource\RenderResource')
            ->node($node)
            ->selected($selected);
    }
}
