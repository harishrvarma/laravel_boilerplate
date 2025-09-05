<?php

namespace Modules\Admin\View\Components\Role\Listing\Edit\Tabs;

use Modules\Core\View\Components\Listing\Edit\Form as CoreForm;

class General extends CoreForm
{
    public function __construct(){
        parent::__construct();
    }
    public function prepareFields(){
        $this->field('name',[
            'id'=>'name',
            'name'=>'role[name]',
            'label'=>'Name',
            'type' => 'text',
        ]);

        $this->field('description',[
            'id'=>'description',
            'name'=>'role[description]',
            'label'=>'Description',
            'type' => 'textarea',
        ]);

        return $this;
    }
}
