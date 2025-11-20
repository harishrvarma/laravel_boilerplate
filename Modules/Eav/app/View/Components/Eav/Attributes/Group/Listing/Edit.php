<?php

namespace Modules\Eav\View\Components\Eav\Attributes\Group\Listing;

use Modules\Core\View\Components\Listing\Edit as CoreEdit;

class Edit extends CoreEdit
{
    protected $tabsClassName = '\Modules\Eav\View\Components\Eav\Attributes\Group\Listing\Edit\Tabs';
    protected $template = 'eav::group.listing.edit';

    public function __construct()
    {
        parent::__construct();
        $this->title('Add/Edit Attribute Groups');
    }

    public function prepareButtons()
    {
        if (canAccess('admin.system.eav.attributes.group.save')) {
            $this->button('save', [
                'id' => 'saveBtn',
                'name' => 'Save',
                'class' => 'btn btn-primary',
            ]);
        }

        if (canAccess('admin.system.eav.attributes.group.save')) {
            $this->button('saveandcontinue', [
                'id' => 'saveandcontinueBtn',
                'name' => 'Save & Continue',
                'class' => 'btn btn-primary',
                'method' => "this.form.action = '" . urlx('admin.system.eav.attributes.group.save', ['continue' => 1]) . "'; this.form.submit();",
            ]);
        }

        $this->button('back', [
            'id' => 'backBtn',
            'name' => 'Back',
            'class' => 'btn btn-secondary',
            'method' => "window.location.href='" . urlx('admin.system.eav.attributes.group.listing') . "'",
        ]);

        return $this;
    }

    public function saveUrl()
    {
        if ($this->row()->getKey()) {
            return urlx('admin.system.eav.attributes.group.save', ['id' => $this->row()->getKey()]);
        }
        return urlx('admin.system.eav.attributes.group.save');
    }
}
