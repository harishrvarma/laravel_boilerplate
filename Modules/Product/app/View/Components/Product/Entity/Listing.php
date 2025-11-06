<?php

namespace Modules\Product\View\Components\Product\Entity;

use Modules\Core\View\Components\Eav\Listing as CoreListing;

class Listing extends CoreListing
{
    /**
     * Grid class reference for this listing.
     *
     * @var string
     */
    protected $gridClassName = "\Modules\Product\View\Components\Product\Entity\Listing\Grid"; 
}
