<?php

namespace Modules\Core\View\Components\Scaffold\Listing\Edit\Tabs;

use Modules\Core\View\Components\Listing\Edit\Form as CoreForm;

class General extends CoreForm
{
    public function __construct(){
        parent::__construct();
    }
    
    public function prepareFields(){
        $this->field('scaffold',[
            'id'=>'scaffold',
            'name'=>'scaffold',
            'label'=>'Module Data (json)',
            'type' => 'textarea',
        ]);
        
        return $this;
    }
}
