<?php

namespace Modules\Product\View\Components\Product\Entity\Listing;

use Modules\Core\View\Components\Listing\Edit as CoreEdit;

class Edit extends CoreEdit
{
    protected $tabsClassName = '\Modules\Product\View\Components\Product\Entity\Listing\Edit\Tabs';

    public function __construct()
    {
        parent::__construct();
        $this->title('Add/Edit Entity');
    }

    public function prepareButtons()
    {
        if (canAccess('admin.product.entity.save')) {
            $this->button('save', [
                'id' => 'saveBtn',
                'name' => 'Save',
                'class' => 'btn btn-primary',
            ]);

            $this->button('saveandcontinue', [
                'id' => 'saveandcontinueBtn',
                'name' => 'Save & Continue',
                'class' => 'btn btn-primary',
                'method' => "this.form.action = '" . urlx('admin.product.entity.save', ['continue' => 1]) . "'; this.form.submit();",
            ]);
        }

        $this->button('back', [
            'id' => 'backBtn',
            'name' => 'Back',
            'class' => 'btn btn-secondary',
            'method' => "window.location.href='" . urlx('admin.product.entity.listing') . "'",
        ]);

        return $this;
    }

    public function saveUrl()
    {
        if ($this->row()->getKey()) {
            return urlx('admin.product.entity.save', ['id' => $this->row()->getKey()]);
        }

        return urlx('admin.product.entity.save');
    }
}
