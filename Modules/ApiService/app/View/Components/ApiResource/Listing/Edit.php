<?php

namespace Modules\ApiService\View\Components\ApiResource\Listing;

use Modules\Core\View\Components\Listing\Edit as CoreEdit;

class Edit extends CoreEdit
{
    protected $tabsClassName = '\Modules\ApiService\View\Components\ApiResource\Listing\Edit\Tabs';

    public function __construct(){
        parent::__construct();
        $this->title('Add/Edit Api Resource');
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
            'method' => "window.location.href='" . urlx('admin.apiresource.listing') . "'",
        ]);
        return $this;
    }

    public function saveUrl(){
        if($this->row()->id){
            return  urlx('admin.apiresource.save',['id'=>$this->row()->id]);
        }
        return  urlx('admin.apiresource.save');
    }
}