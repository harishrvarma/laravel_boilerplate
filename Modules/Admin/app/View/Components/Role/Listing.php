<?php
namespace Modules\Admin\View\Components\Role;

use Modules\Core\View\Components\Listing as CoreListing;
class Listing extends CoreListing
{

    protected $gridClassName = "\Modules\Admin\View\Components\Role\Listing\Grid"; 

    public function prepareButtons(){
        $this->button('add', [
            'label' => 'Add Role',
            'route' =>route('admin.role.add'),
        ]);
    }
}