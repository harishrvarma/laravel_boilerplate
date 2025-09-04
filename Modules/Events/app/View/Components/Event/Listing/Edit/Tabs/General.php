<?php

namespace Modules\Events\View\Components\Event\Listing\Edit\Tabs;

use Modules\Core\View\Components\Listing\Edit\Form as CoreForm;

class General extends CoreForm
{
    public function __construct(){
        parent::__construct();
    }
    
    public function prepareFields(){

        $this->field('name',[
            'id'=>'name',
            'name'=>'event[name]',
            'label'=>'Name',
            'type' => 'text',
        ]);
        $this->field('code',[
            'id'=>'code',
            'name'=>'event[code]',
            'label'=>'Code',
            'type' => 'text',
        ]);
        $this->field('description',[
            'id'=>'description',
            'name'=>'event[description]',
            'label'=>'Description',
            'type' => 'textarea',
        ]);
        $this->field('status',[
            'id'=>'status',
            'name'=>'event[status]',
            'label'=>'Status',
            'type' => 'select',
            'options'=> [1=>'Active',2=>'Inactive'],
        ]);
        return $this;
    }
}
