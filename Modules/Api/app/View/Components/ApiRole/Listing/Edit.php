<?php

namespace Modules\Api\View\Components\ApiRole\Listing;

use Modules\Core\View\Components\Listing\Edit as CoreEdit;

class Edit extends CoreEdit
{
    protected $tabsClassName = '\Modules\Api\View\Components\ApiRole\Listing\Edit\Tabs';

    public function __construct(){
        parent::__construct();
        $this->title('Add/Edit Api Role');
    }

    public function prepareButtons(){
        if(canAccess('admin.apirole.save')){
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
            'method' => "window.location.href='" . urlx('admin.apirole.listing') . "'",
        ]);
        return $this;
    }

    public function saveUrl(){
        if($this->row()->id){
            return  urlx('admin.apirole.save',['id'=>$this->row()->id]);
        }
        return  urlx('admin.apirole.save');
    }
}