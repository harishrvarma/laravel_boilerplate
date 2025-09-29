<?php

namespace Modules\Core\View\Components\Scaffold\Listing;

use Modules\Core\View\Components\Listing\Edit as CoreEdit;

class Edit extends CoreEdit
{
    protected $tabsClassName = '\Modules\Core\View\Components\Scaffold\Listing\Edit\Tabs';

    public function __construct(){
        parent::__construct();
        $this->title('Add Module');
    }

    public function prepareButtons(){
        if(canAccess('admin.scaffold.save')){
            $this->button('save',[
                'id' => 'saveBtn',
                'name'=>'Generate',
                'class'=>'btn btn-primary',
            ]);
        }
        return $this;
    }

    public function saveUrl(){
        return urlx('admin.scaffold.save');
    }
}