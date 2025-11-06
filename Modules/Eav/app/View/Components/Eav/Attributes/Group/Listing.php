<?php

namespace Modules\Eav\View\Components\Eav\Attributes\Group;

use Modules\Core\View\Components\Listing as CoreListing;

class Listing extends CoreListing
{
    /**
     * Grid class reference for this listing.
     *
     * @var string
     */
    protected $gridClassName = "\Modules\Eav\View\Components\Eav\Attributes\Group\Listing\Grid"; 
}
