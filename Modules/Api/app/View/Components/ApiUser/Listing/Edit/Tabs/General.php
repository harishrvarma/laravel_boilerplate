<?php

namespace Modules\Api\View\Components\ApiUser\Listing\Edit\Tabs;

use Modules\Core\View\Components\Listing\Edit\Form as CoreForm;

class General extends CoreForm
{
    public function __construct(){
        parent::__construct();
    }
    public function prepareFields(){
        $this->field('name',[
            'id'=>'name',
            'name'=>'apiuser[name]',
            'label'=>'Name',
            'type' => 'text',
        ]);
        $this->field('email',[
            'id'=>'email',
            'name'=>'apiuser[email]',
            'label'=>'Email',
            'type' => 'text',
        ]);
        $this->field('password',[
            'id'=>'password',
            'name'=>'apiuser[password]',
            'label'=>'Password',
            'type' => 'password',
        ]);

        $this->field('role_id',[
            'id'=>'role_id',
            'name'=>'apirole[role_id][]',
            'label'=>'Role',
            'type' => 'select',
            'multiselect' => true,
            'relation' => 'role',
            'options' => $this->roleOptions()
        ]);

        $this->field('status',[
            'id'=>'status',
            'name'=>'apiuser[status]',
            'label'=>'Status',
            'type' => 'select',
            'options'=>[
                '1' => 'Active',
                '2' => 'Inactive',]
        ]);
        return $this;
    }

    public function renderMe($renderer,$field) {
        return view($renderer,['field' => $field , 'row' => $this->row()]);
    }

    public function roleOptions(){
        $roles = \Modules\Api\Models\ApiRole::all();
        $options = [];
        foreach($roles as $role){
            $options[$role->id] = $role->name;
        }
        return $options;
    }
}
