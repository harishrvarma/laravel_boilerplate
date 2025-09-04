<?php
namespace Modules\Events\View\Components\Listener;

use Modules\Core\View\Components\Listing as CoreListing;
class Listing extends CoreListing
{

    protected $gridClassName = "\Modules\Events\View\Components\Listener\Listing\Grid"; 


    public function prepareButtons(){
        $this->button('add', [
            'route' =>route('admin.listener.add',['event_id'=>request('id')]),
            'label' => 'Add Listener',
        ]);
    }
}