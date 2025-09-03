<?php

namespace Modules\Admin\View\Components\role\Listing\Edit\Tabs;

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
        return $this;
    }

    public function renderMe($renderer,$field) {
        return view($renderer,['field' => $field , 'row' => $this->row()]);
    }
}
