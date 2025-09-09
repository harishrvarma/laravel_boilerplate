<?php

namespace Modules\Api\View\Components\ApiUser\Listing;

use Modules\Core\View\Components\Listing\Edit as CoreEdit;

class Edit extends CoreEdit
{
    protected $tabsClassName = '\Modules\Api\View\Components\ApiUser\Listing\Edit\Tabs';

    public function __construct(){
        parent::__construct();
        $this->title('Add/Edit Api User');

    }

    public function prepareButtons(){
        if(canAccess('admin.apiuser.save')){
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
            'method' => "window.location.href='" . urlx('admin.apiuser.listing') . "'",
        ]);
        return $this;
    }

    public function saveUrl(){
        if($this->row()->id){
            return  urlx('admin.apiuser.save',['id'=>$this->row()->id]);
        }
        return  urlx('admin.apiuser.save');
    }
}