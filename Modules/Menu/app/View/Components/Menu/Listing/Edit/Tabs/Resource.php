<?php

namespace Modules\Menu\View\Components\Menu\Listing\Edit\Tabs;

use Modules\Admin\Models\Resource as ModelResource;
use Modules\Core\View\Components\Block;

class Resource extends Block
{
    protected $template = 'menu::components.menu.listing.edit.tabs.resource';

    protected $selected = null;

    public function __construct()
    {
        parent::__construct();

        // if editing, pre-select the saved resource_id
        $this->selected = request('id')
            ? \Modules\Menu\Models\Menu::find(request('id'))?->resource_id
            : null;
    }

    /**
     * All resources ordered for tree build
     */
    public function resources()
    {
        return ModelResource::orderBy('id')->get();
    }

    /**
     * Build tree from flat resources
     */
    public function buildTree($resources, $parentId = null)
    {
        return $resources
            ->where('parent_id', $parentId)
            ->filter(function ($resource) {
                if (!$resource->route_name) return true; // Keep folders
                $action = last(explode('.', $resource->code)); 
                return $resource->route_name === ($resource->code . '.*') || in_array($action, ['listing', 'add']);
            })
            ->map(function ($resource) use ($resources) {
                $resource->children = $this->buildTree($resources, $resource->id);
                return $resource;
            });
    }

    // public function buildTree($resources, $parentId = null)
    // {
    //     return $resources
    //         ->where('parent_id', $parentId)
    //         ->map(function ($resource) use ($resources) {
    //             $resource->children = $this->buildTree($resources, $resource->id);
    //             return $resource;
    //         });
    // }
    /**
     * Pass single node into RenderResource block
     */
    public function getResourceBlock($node, $selected)
    {
        return $this->block('\Modules\Menu\View\Components\Menu\Listing\Edit\Tabs\Resource\RenderResource')
            ->node($node)
            ->selected($selected);
    }

    /**
     * Currently selected resource id
     */
    public function selected()
    {
        return $this->selected;
    }
}
