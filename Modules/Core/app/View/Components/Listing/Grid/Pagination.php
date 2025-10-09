<?php
 
namespace Modules\Core\View\Components\Listing\Grid;
 
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\View\Components\Block;
 
class Pagination extends Block
{
    public LengthAwarePaginator $paginator;
    public array $params;
    public int $perPage;
    public array $perPageOptions;
    public $template = 'core::listing.grid.pagination';
 
    /**
     * Create a new component instance.
     */
    public function __construct(LengthAwarePaginator $paginator, array $params = [], int $perPage = 10, $grid = null)
    {
        $this->paginator = $paginator;
        $this->params    = $params;
        $this->perPage   = $perPage;
        $this->grid      = $grid;
        $this->perPageOptions = !empty($perPageOptions) ? $perPageOptions : [5, 10, 20, 30, 40, 50];
    }
 
    public function queryString(array $extra = []): string
    {
        return http_build_query(array_merge($this->params, $extra));
    }
}