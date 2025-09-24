<?php

namespace Modules\Settings\View\Components\Listing;

use Modules\Core\View\Components\Listing\Edit as CoreEdit;

class Edit extends CoreEdit
{
    protected $tabsClassName = 'Modules\Settings\View\Components\Listing\Edit\GroupTabs';

    public function __construct(){
        parent::__construct();
        $this->title('Add Group');
    }

    public function prepareButtons(){
        if(canAccess('admin.config.save')){
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
            'method' => "window.location.href='" . urlx('admin.config.listing',['tab'=> 1], true) . "'",
        ]);
        return $this;
    }

    public function saveUrl(){
        if($this->row()->id){
            return  route('admin.config.save',['id'=>$this->row()->id]);
        }
        return  route('admin.config.save');
    }
}