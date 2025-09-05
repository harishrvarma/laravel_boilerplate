<?php

namespace Modules\Admin\View\Components\Admin\Listing;

use Modules\Core\View\Components\Listing\Edit as CoreEdit;

class Edit extends CoreEdit
{
    protected $tabsClassName = '\Modules\Admin\View\Components\Admin\Listing\Edit\Tabs';

    public function __construct(){
        parent::__construct();
        $this->title('Add/Edit Admin');
    }

    public function prepareButtons(){
        if(canAccess('admin.admin.save')){
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
            'method' => "window.location.href='" . urlx('admin.admin.listing') . "'",
        ]);
        return $this;
    }

    public function saveUrl(){
        if($this->row()->id){
            return urlx('admin.admin.save',['id'=>$this->row()->id]);
        }
        return urlx('admin.admin.save');
    }
}