<?php

namespace Modules\Cron\View\Components\Cron\Listing;

use Modules\Core\View\Components\Listing\Edit as CoreEdit;

class Edit extends CoreEdit
{
    protected $tabsClassName = '\Modules\Cron\View\Components\Cron\Listing\Edit\Tabs';

    public function __construct(){
        parent::__construct();
        $this->title('Add/Edit Cron');
    }

    public function prepareButtons(){
        if(canAccess('admin.system.cron.save')){
            $this->button('save',[
                'id' => 'saveBtn',
                'name'=>'Save',
                'class'=>'btn btn-primary',
            ]);
        }

         $this->button('back',[
            'id' => 'backBtn',
            'name'=>'Back',
            'class'=>'btn btn-secondary',
            'method' => "window.location.href='" . urlx('admin.system.cron.listing') . "'",
        ]);
        return $this;
    }

    public function saveUrl(){
        if($this->row()->getKey()){
            return  urlx('admin.system.cron.save',['id'=>$this->row()->getKey()]);
        }
        return  urlx('admin.system.cron.save');
    }
}