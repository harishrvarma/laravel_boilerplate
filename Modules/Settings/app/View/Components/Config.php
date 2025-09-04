<?php

namespace Modules\Settings\View\Components;

use Modules\Core\View\Components\Listing\Edit as CoreEdit;

class Config extends CoreEdit
{
    protected $tabsClassName = 'Modules\Settings\View\Components\Listing\Edit\Tabs';

    public function __construct(){
        parent::__construct();
        $this->title('Config Groups');
    }

    public function saveUrl(){
        $id = request('tab');
        if($id){
            return  route('admin.config.saveConfig',['id'=>$id]);
        }
        return  route('admin.config.saveConfig');
    }

}