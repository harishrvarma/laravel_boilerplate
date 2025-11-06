<?php

namespace Modules\Eav\View\Components\Eav\Attributes;

use Modules\Core\View\Components\Listing as CoreListing;

class Listing extends CoreListing
{
    /**
     * Grid class reference for this listing.
     *
     * @var string
     */
    protected $gridClassName = "\Modules\Eav\View\Components\Eav\Attributes\Listing\Grid"; 
}
