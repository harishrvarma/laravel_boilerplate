<?php

namespace Modules\ApiService\View\Components\ApiRole\Listing\Edit\Tabs;

use Modules\Core\View\Components\Listing\Edit\Form as CoreForm;

class General extends CoreForm
{
    public function __construct(){
        parent::__construct();
    }
    public function prepareFields(){
        $this->field('name',[
            'id'=>'name',
            'name'=>'apirole[name]',
            'label'=>'Name',
            'type' => 'text',
        ]);
        $this->field('description',[
            'id'=>'description',
            'name'=>'apirole[description]',
            'label'=>'Description',
            'type' => 'textarea',
        ]);
        $this->field('resource_id',[
            'id'=>'resource_id',
            'name'=>'apiresource[resource_id][]',
            'label'=>'Resource',
            'type' => 'select',
            'multiselect' => true,
            'relation' => 'resource',
            'options' => $this->resourceOptions()
        ]);
        return $this;
    }

    public function renderMe($renderer,$field) {
        return view($renderer,['field' => $field , 'row' => $this->row()]);
    }

    public function resourceOptions(){
        $resources = \Modules\ApiService\Models\ApiResource::all();
        $options = [];
        foreach($resources as $resource){
            $options[$resource->id] = $resource->code;
        }
        return $options;
    }
}
