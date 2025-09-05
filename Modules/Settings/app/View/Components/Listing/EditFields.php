<?php

namespace Modules\Settings\View\Components\Listing;

use Modules\Core\View\Components\Listing\Edit as CoreEdit;

class EditFields extends CoreEdit
{
    protected $tabsClassName = 'Modules\Settings\View\Components\Listing\Edit\FieldTabs';

    public function __construct(){
        parent::__construct();
        $this->title('Add Fields');
    }

    public function prepareButtons(){
        if(canAccess('admin.config.saveFields')){
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
            'method' => "window.location.href='" . urlx('admin.config.listing') . "'",
        ]);
        return $this;
    }

    public function saveUrl(){
        if($this->row()->id){
            return  urlx('admin.config.saveFields',['id'=>$this->row()->id]);
        }
        return  urlx('admin.config.saveFields');
    }
}