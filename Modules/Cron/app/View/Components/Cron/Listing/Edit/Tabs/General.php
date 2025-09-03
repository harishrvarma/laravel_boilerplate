<?php

namespace Modules\Cron\View\Components\Cron\Listing\Edit\Tabs;

use Modules\Core\View\Components\Listing\Edit\Form as CoreForm;

class General extends CoreForm
{
    public function __construct(){
        parent::__construct();
    }
    
    public function prepareFields(){

        $this->field('name',[
            'id'=>'name',
            'name'=>'cron[name]',
            'label'=>'Name',
            'type' => 'text',
        ]);
        $this->field('command',[
            'id'=>'command',
            'name'=>'cron[command]',
            'label'=>'Command',
            'type' => 'text',
        ]);
        $this->field('expression',[
            'id'=>'expression',
            'name'=>'cron[expression]',
            'label'=>'expression',
            'type' => 'text',
        ]);
        $this->field('is_active',[
            'id'=>'is_active',
            'name'=>'cron[is_active]',
            'label'=>'Status',
            'type' => 'select',
            'options'=> [1=>'Active',2=>'Inactive'],
        ]);
        return $this;
    }
}
