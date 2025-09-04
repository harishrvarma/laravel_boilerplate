<?php

namespace Modules\Admin\View\Components\AdminRole\Listing\Edit\Tabs;

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

    public function renderMe($renderer,$field) {
        return view($renderer,['field' => $field , 'row' => $this->row()]);
    }

}
