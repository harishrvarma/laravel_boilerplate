<?php

namespace Modules\Menu\View\Components\Menu\Listing\Edit\Tabs\RenderTree;

use Modules\Core\View\Components\Block;

class RenderMenu extends Block
{
    public $node;
    public $tree;

    protected $template = 'menu::components.menu.listing.edit.tabs.renderTree.renderMenu';

    public function node($node = null)
    {
        if (!is_null($node)) {
            $this->node = $node;
            return $this;
        }
        return $this->node;
    }

    public function tree($tree = null)
    {
        if(!is_null($tree)) {
            $this->tree = $tree;
            return $this;
        }
        return $this->tree;
    }
}
