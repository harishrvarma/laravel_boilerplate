<?php

namespace Modules\Cron\View\Components\Cron\Listing\Edit\Tabs;

use Modules\Core\View\Components\Listing\Edit\Form as CoreForm;

class General extends CoreForm
{
    public function __construct(){
        parent::__construct();
    }
    
    public function prepareFields()
    {
        $this->field('name', [
            'id' => 'name',
            'name' => 'cron[name]',
            'label' => 'Name',
            'type' => 'text',
        ]);

        $this->field('command', [
            'id' => 'command',
            'name' => 'cron[command]',
            'label' => 'Command',
            'type' => 'text',
        ]);

        $this->field('expression', [
            'id' => 'expression',
            'name' => 'cron[expression]',
            'label' => 'Expression',
            'type' => 'text',
        ]);

        $this->field('class', [
            'id' => 'class',
            'name' => 'cron[class]',
            'label' => 'Class',
            'type' => 'text',
        ]);

        $this->field('method', [
            'id' => 'method',
            'name' => 'cron[method]',
            'label' => 'Method',
            'type' => 'text',
        ]);

        $this->field('frequency', [
            'id' => 'frequency',
            'name' => 'cron[frequency]',
            'label' => 'Frequency',
            'type' => 'text',
            'placeholder' => '* * * * *',
        ]);

        $this->field('is_active', [
            'id' => 'is_active',
            'name' => 'cron[is_active]',
            'label' => 'Status',
            'type' => 'select',
            'options' => [1 => 'Active', 0 => 'Inactive'],
        ]);

        return $this;
    }
}
