<?php

namespace Modules\Events\View\Components\Listener\Listing;

use Modules\Core\View\Components\Listing\Edit as CoreEdit;

class Edit extends CoreEdit
{
    protected $tabsClassName = '\Modules\Events\View\Components\Listener\Listing\Edit\Tabs';

    public function __construct()
    {
        parent::__construct();
        $this->title('Add/Edit Listener');
    }

    public function prepareButtons(){
        if(canAccess('admin.system.listener.save')){
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
            'method' => "window.location.href='". url()->previous() . "'",
        ]);
        return $this;
    }

    public function saveUrl(){
        if($this->row()->id){
            return  urlx('admin.system.listener.save',['id'=>$this->row()->id,'event_id'=>0]);
        }
        return  urlx('admin.system.listener.save',['id'=>0,'event_id'=>request('event_id')]);
    }
}