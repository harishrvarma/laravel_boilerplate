<?php

namespace Modules\Events\View\Components\Event\Listing;

use Modules\Core\View\Components\Listing\Edit as CoreEdit;

class Edit extends CoreEdit
{
    protected $tabsClassName = '\Modules\Events\View\Components\Event\Listing\Edit\Tabs';

    public function __construct()
    {
        parent::__construct();
        $this->title('Add/Edit Event');
    }

    public function prepareButtons(){
        if(canAccess('admin.system.event.save')){
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
            'method' => "window.location.href='" . urlx('admin.system.event.listing',[],true) . "'",
        ]);
        return $this;
    }

    public function saveUrl(){
        if($this->row() && $this->row()->id){
            return  urlx('admin.system.event.save',['id'=>$this->row()->id]);
        }
        return  urlx('admin.system.event.save');
    }
}