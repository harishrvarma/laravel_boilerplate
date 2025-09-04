<?php
namespace Modules\Events\View\Components\Event;

use Modules\Core\View\Components\Listing as CoreListing;
class Listing extends CoreListing
{

    protected $gridClassName = "\Modules\Events\View\Components\Event\Listing\Grid"; 


    public function prepareButtons(){
        $this->button('add', [
            'route' =>route('admin.event.add'),
            'label' => 'Add Event',
        ]);
    }
}