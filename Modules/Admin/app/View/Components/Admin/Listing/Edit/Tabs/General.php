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
        $this->field('phone',[
            'id'=>'phone',
            'name'=>'admin[phone]',
            'label'=>'Phone No',
            'type' => 'text',
        ]);
        $this->field('password',[
            'id'=>'password',
            'name'=>'admin[password]',
            'label'=>'Password',
            'type' => 'password',
        ]);
        return $this;
    }
}
