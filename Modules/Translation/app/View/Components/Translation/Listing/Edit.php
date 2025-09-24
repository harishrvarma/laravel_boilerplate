<?php

namespace Modules\Translation\View\Components\Translation\Listing;

use Modules\Core\View\Components\Listing\Edit as CoreEdit;

class Edit extends CoreEdit
{
    protected $tabsClassName = 'Modules\Translation\View\Components\Translation\Listing\Edit\Tabs';

    public function __construct(){
        parent::__construct();
        $this->title('Add/Edit Translation');
    }

    public function prepareButtons(){
        if(canAccess('admin.translation.save')){
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
            'method' => "window.location.href='" . urlx('admin.translation.listing') . "'",
        ]);
        return $this;
    }

    public function saveUrl(){
        if($this->row()->id){
            return urlx('admin.translation.save',['id'=>$this->row()->id]);
        }
        return urlx('admin.translation.save');
    }
}