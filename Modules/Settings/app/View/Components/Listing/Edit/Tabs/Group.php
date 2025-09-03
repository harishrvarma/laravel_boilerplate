<?php

namespace Modules\Settings\View\Components\Listing\Edit\Tabs;

use Modules\Core\View\Components\Listing\Edit\Form as CoreForm;

class Group extends CoreForm
{
    public function __construct(){
        parent::__construct();
    }

    public function prepareFields()
    {
        $this->field('name',[
            'id'=>'name',
            'name'=>'config[name]',
            'label'=>'Name',
            'type' => 'text',
        ]);

        $this->field('description',[
            'id'=>'description',
            'name'=>'config[description]',
            'label'=>'Description',
            'type' => 'text',
        ]);
        return $this;
    }
}
