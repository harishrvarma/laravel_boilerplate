<?php

namespace Modules\Admin\View\Components\Admin\Listing\Edit\Tabs;

use Modules\Core\View\Components\Listing\Edit\Form as CoreForm;

class General extends CoreForm
{
    public function __construct(){
        parent::__construct();
    }
    
    public function prepareFields(){
        $this->field('first_name',[
            'id'=>'first_name',
            'name'=>'admin[first_name]',
            'label'=>'First Name',
            'type' => 'text',
        ]);
        $this->field('last_name',[
            'id'=>'last_name',
            'name'=>'admin[last_name]',
            'label'=>'Last Name',
            'type' => 'text',
        ]);
        $this->field('email',[
            'id'=>'email',
            'name'=>'admin[email]',
            'label'=>'Email',
            'type' => 'text',
        ]);
        $this->field('username',[
            'id'=>'username',
            'name'=>'admin[username]',
            'label'=>'Username',
            'type' => 'text',
        ]);
        $this->field('status',[
            'id'=>'status',
            'name'=>'admin[status]',
            'label'=>'Status',
            'type' => 'select',
            'options' => [1=>'Active',2=>'Inactive'],
        ]);
        $this->field('role_id',[
            'id'=>'role_id',
            'name'=>'role[role_id][]',
            'label'=>'Role',
            'type' => 'select',
            'multiselect' => true,
            'relation' => 'roles',
            'options' => $this->roleOptions()
        ]);
        $this->field('password',[
            'id'=>'password',
            'name'=>'admin[password]',
            'label'=>'Password',
            'type' => 'password',
        ]);
        return $this;
    }

    public function roleOptions(){
        $roles = \Modules\Admin\Models\AdminRole::all();
        $options = [];
        foreach($roles as $role){
            $options[$role->id] = $role->name;
        }
        return $options;
    }
}
