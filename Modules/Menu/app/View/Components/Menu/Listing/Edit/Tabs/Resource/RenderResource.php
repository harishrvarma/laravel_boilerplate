<?php

namespace Modules\Menu\View\Components\Menu\Listing\Edit\Tabs\Resource;

use Modules\Core\View\Components\Block;

class RenderResource extends Block
{
    protected $template = 'menu::components.menu.listing.edit.tabs.resource.render';

    protected $node;
    protected $selected;

    public function node($node)
    {
        $this->node = $node;
        return $this;
    }

    public function selected($selected)
    {
        $this->selected = $selected;
        return $this;
    }

    public function nodeData()
    {
        return $this->node;
    }

    public function isSelected()
    {
        return $this->selected == $this->node->id;
    }
}