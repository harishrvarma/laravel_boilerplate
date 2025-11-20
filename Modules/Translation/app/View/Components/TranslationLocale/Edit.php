<?php

namespace Modules\Translation\View\Components\TranslationLocale;

use Modules\Core\View\Components\Listing\Edit as CoreEdit;

class Edit extends CoreEdit
{
    protected $tabsClassName = 'Modules\Translation\View\Components\TranslationLocale\Edit\Tabs';

    public function __construct(){
        parent::__construct();
        $this->title('Add Language');
    }

    public function prepareButtons(){
        if(canAccess('admin.system.translation.saveLocale')){
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
            'method' => "window.location.href='" . urlx('admin.system.translation.listing') . "'",
        ]);
        return $this;
    }

    public function saveUrl(){
        if($this->row()->id){
            return urlx('admin.system.translation.saveLocale',['id'=>$this->row()->id]);
        }
        return urlx('admin.system.translation.saveLocale');
    }
}