<?php

namespace Modules\Admin\View\Components\AdminResource\Listing\Edit\Tabs;

use Modules\Core\View\Components\Listing\Edit\Form as CoreForm;

class General extends CoreForm
{
    public function __construct(){
        parent::__construct();
    }
    public function prepareFields(){
        $this->field('code',[
            'id'=>'code',
            'name'=>'resource[code]',
            'label'=>'code',
            'type' => 'text',
        ]);

        $this->field('description',[
            'id'=>'description',
            'name'=>'resource[description]',
            'label'=>'Description',
            'type' => 'textarea',
        ]);
        return $this;
    }

    public function renderMe($renderer,$field) {
        return view($renderer,['field' => $field , 'row' => $this->row()]);
    }
}
