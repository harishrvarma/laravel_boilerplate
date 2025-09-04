<?php

namespace Modules\ApiService\View\Components\ApiResource\Listing\Edit\Tabs;

use Modules\Core\View\Components\Listing\Edit\Form as CoreForm;

class General extends CoreForm
{
    public function __construct(){
        parent::__construct();
    }
    public function prepareFields(){
        $this->field('code',[
            'id'=>'code',
            'name'=>'apiresource[code]',
            'label'=>'Code',
            'type' => 'text',
        ]);
        $this->field('description',[
            'id'=>'description',
            'name'=>'apiresource[description]',
            'label'=>'Description',
            'type' => 'textarea',
        ]);
        return $this;
    }

    public function renderMe($renderer,$field) {
        return view($renderer,['field' => $field , 'row' => $this->row()]);
    }
}
