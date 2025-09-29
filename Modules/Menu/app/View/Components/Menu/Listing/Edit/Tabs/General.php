<?php

namespace Modules\Menu\View\Components\Menu\Listing\Edit\Tabs;

use Modules\Core\View\Components\Listing\Edit\Form as CoreForm;
use Modules\Menu\Models\Menu;

class General extends CoreForm
{
    public function __construct()
    {
        parent::__construct();
    }

    public function prepareFields()
    {
        $this->field('title', [
            'id'    => 'title',
            'name'  => 'menu[title]',
            'label' => 'Title',
            'type'  => 'text',
        ]);
    
        $this->field('item_type', [
            'id'      => 'item_type',
            'name'    => 'menu[item_type]',
            'label'   => 'Type',
            'type'    => 'select',
            'options' => [
                'folder' => 'Folder',
                'file'   => 'File',
            ],
        ]);
    
        $this->field('icon', [
            'id'    => 'icon',
            'name'  => 'menu[icon]',
            'label' => 'Icon (FontAwesome class)',
            'type'  => 'text',
        ]);
    
        $this->field('area', [
            'id'      => 'area',
            'name'    => 'menu[area]',
            'label'   => 'Area',
            'type'    => 'select',
            'options' => [
                'admin'    => 'Admin',
                'frontend' => 'Frontend',
                'api'      => 'API',
            ],
        ]);
    
        $this->field('order_no', [
            'id'    => 'order_no',
            'name'  => 'menu[order_no]',
            'label' => 'Order',
            'type'  => 'number',
        ]);
    
        $this->field('is_active', [
            'id'      => 'is_active',
            'name'    => 'menu[is_active]',
            'label'   => 'Active',
            'type'    => 'select',
            'options' => [1 => 'Yes', 0 => 'No'],
        ]);
    
        return $this;
    }
}
