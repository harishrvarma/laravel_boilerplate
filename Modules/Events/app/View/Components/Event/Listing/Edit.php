<?php

namespace Modules\Events\View\Components\Event\Listing;

use Modules\Core\View\Components\Listing\Edit as CoreEdit;

class Edit extends CoreEdit
{
    protected $tabsClassName = '\Modules\Events\View\Components\Event\Listing\Edit\Tabs';

    public function __construct(){
        parent::__construct();
    }

    public function prepareButtons(){
        $this->button('save',[
            'id' => 'saveBtn',
            'name'=>'Save',
            'class'=>'btn btn-primary',
        ]);

         $this->button('back',[
            'id' => 'backBtn',
            'name'=>'Back',
            'class'=>'btn btn-secondary',
            'method' => "window.location.href='" . urlx('admin.event.listing',[],true) . "'",
        ]);
        return $this;
    }

    public function saveUrl(){
        if($this->row() && $this->row()->id){
            return  urlx('admin.event.save',['id'=>$this->row()->id]);
        }
        return  urlx('admin.event.save');
    }
}