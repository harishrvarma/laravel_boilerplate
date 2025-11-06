<?php

namespace Modules\Eav\View\Components\Eav\Attributes\Listing;

use Modules\Core\View\Components\Listing\Edit as CoreEdit;

class Edit extends CoreEdit
{
    protected $tabsClassName = '\Modules\Eav\View\Components\Eav\Attributes\Listing\Edit\Tabs';
    protected $template = 'eav::listing.edit';

    public function __construct()
    {
        parent::__construct();
        $this->title('Add/Edit Attribute');
    }

    public function prepareButtons()
    {
        if (canAccess('admin.eav.attributes.save')) {
            $this->button('save', [
                'id' => 'saveBtn',
                'name' => 'Save',
                'class' => 'btn btn-primary',
            ]);
        }

        if (canAccess('admin.eav.attributes.save')) {
            $this->button('saveandcontinue', [
                'id' => 'saveandcontinueBtn',
                'name' => 'Save & Continue',
                'class' => 'btn btn-primary',
                'method' => "this.form.action = '" . urlx('admin.eav.attributes.save', ['continue' => 1]) . "'; this.form.submit();",
            ]);
        }

        $this->button('back', [
            'id' => 'backBtn',
            'name' => 'Back',
            'class' => 'btn btn-secondary',
            'method' => "window.location.href='" . urlx('admin.eav.attributes.listing') . "'",
        ]);

        return $this;
    }

    public function saveUrl()
    {
        if ($this->row()->getKey()) {
            return urlx('admin.eav.attributes.save', ['id' => $this->row()->getKey()]);
        }
        return urlx('admin.eav.attributes.save');
    }
}
