<?php

namespace Modules\Admin\View\Components\Role\Listing;

use Modules\Core\View\Components\Listing\Edit as CoreEdit;

class Edit extends CoreEdit
{
    protected $tabsClassName = '\Modules\Admin\View\Components\Role\Listing\Edit\Tabs';

    public function __construct(){
        parent::__construct(); 
        $this->title('Add/Edit Roles');
    }

    public function prepareButtons(){
        if(canAccess('admin.role.save')){
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
            'method' => "window.location.href='" . urlx('admin.role.listing') . "'",
        ]);
        return $this;
    }

    public function saveUrl(){
        if($this->row()->id){
            return  urlx('admin.role.save',['id'=>$this->row()->id]);
        }
        return  urlx('admin.role.save');
    }
}