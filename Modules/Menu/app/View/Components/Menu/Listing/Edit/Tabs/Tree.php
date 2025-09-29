<?php

namespace Modules\Menu\View\Components\Menu\Listing\Edit\Tabs;

use Modules\Core\View\Components\Block;
use Modules\Menu\Models\Menu;

class Tree extends Block
{
    protected $template = 'menu::components.menu.listing.edit.tabs.tree'; 

    public function __construct()
    {
        parent::__construct();
    }

    public function menus()
    {
        return Menu::orderBy('order_no')->get();
    }

    public function buildTree($menus, $parentId = null)
    {
        return $menus
            ->where('parent_id', $parentId)
            ->map(function ($menu) use ($menus) {
                $menu->children = $this->buildTree($menus, $menu->id);
                return $menu;
            });
    }

    public function getMenuBlock($node)
    {
        return $this->block('\Modules\Menu\View\Components\Menu\Listing\Edit\Tabs\RenderTree\RenderMenu')
            ->node($node)->tree($this);
    }
}
