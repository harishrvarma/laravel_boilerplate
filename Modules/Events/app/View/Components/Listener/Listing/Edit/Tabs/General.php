<?php

namespace Modules\Events\View\Components\Listener\Listing\Edit\Tabs;

use Modules\Core\View\Components\Listing\Edit\Form as CoreForm;

class General extends CoreForm
{
    public function __construct(){
        parent::__construct();
    }
    
    public function prepareFields(){

        $this->field('name',[
            'id'=>'name',
            'name'=>'listener[name]',
            'label'=>'Name',
            'type' => 'text',
        ]);

        $this->field('component',[
            'id'=>'component',
            'name'=>'listener[component]',
            'label'=>'Component',
            'type' => 'text',
        ]);

        $this->field('method',[
            'id'=>'method',
            'name'=>'listener[method]',
            'label'=>'Method',
            'type' => 'text',
        ]);

        $this->field('order_no',[
            'id'=>'order_no',
            'name'=>'listener[order_no]',
            'label'=>'Order No',
            'type' => 'number',
        ]);
        
        $this->field('status',[
            'id'=>'status',
            'name'=>'listener[status]',
            'label'=>'Status',
            'type' => 'select',
            'options'=> [1=>'Active',2=>'Inactive'],
        ]);
        return $this;
    }
}
