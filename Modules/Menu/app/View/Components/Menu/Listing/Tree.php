<?php

namespace Modules\Menu\View\Components\Menu\Listing;

use Modules\Core\View\Components\Listing\Edit as CoreEdit;

class Tree extends CoreEdit
{
    protected $tabsClassName = '\Modules\Menu\View\Components\Menu\Listing\Edit\TreeTabs';

    public function __construct()
    {
        parent::__construct();
        $this->title('Add/Edit Tree');
    }

    public function prepareButtons()
    {
        if (canAccess('admin.menu.saveTree')) {
            $this->button('save', [
                'id'    => 'saveBtn',
                'name'  => 'Save',
                'class' => 'btn btn-primary',
            ]);
        }

        $this->button('back', [
            'id'     => 'backBtn',
            'name'   => 'Back',
            'class'  => 'btn btn-secondary',
            'method' => "window.location.href='" . urlx('admin.menu.listing') . "'",
        ]);

        return $this;
    }

    public function saveUrl()
    {
        return urlx('admin.menu.saveTree');
    }
}
