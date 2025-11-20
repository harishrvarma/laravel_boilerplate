<?php

namespace Modules\Eav\View\Components\Eav\Entities\Listing;

use Modules\Core\View\Components\Listing\Edit as CoreEdit;

class Edit extends CoreEdit
{
    protected $tabsClassName = '\Modules\Eav\View\Components\Eav\Entities\Listing\Edit\Tabs';

    public function __construct()
    {
        parent::__construct();
        $this->title('Add/Edit Entities');
    }

    public function prepareButtons()
    {
        if (canAccess('admin.system.eav.entities.save')) {
            $this->button('save', [
                'id' => 'saveBtn',
                'name' => 'Save',
                'class' => 'btn btn-primary',
            ]);
        }

        if (canAccess('admin.system.eav.entities.save')) {
            $this->button('saveandcontinue', [
                'id' => 'saveandcontinueBtn',
                'name' => 'Save & Continue',
                'class' => 'btn btn-primary',
                'method' => "this.form.action = '" . urlx('admin.system.eav.entities.save', ['continue' => 1]) . "'; this.form.submit();",
            ]);
        }

        $this->button('back', [
            'id' => 'backBtn',
            'name' => 'Back',
            'class' => 'btn btn-secondary',
            'method' => "window.location.href='" . urlx('admin.system.eav.entities.listing') . "'",
        ]);

        return $this;
    }

    public function saveUrl()
    {
        if ($this->row()->getKey()) {
            return urlx('admin.system.eav.entities.save', ['id' => $this->row()->getKey()]);
        }
        return urlx('admin.system.eav.entities.save');
    }
}
