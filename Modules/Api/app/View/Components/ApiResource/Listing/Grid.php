<?php
namespace Modules\Api\View\Components\ApiResource\Listing;

use Modules\Api\Models\ApiResource;
use Modules\Core\View\Components\Listing\Grid as CoreGrid;
class Grid extends CoreGrid
{
    public function __construct()
    {
        parent::__construct();
        $this->title('Manage Api Resources');
    }

    public function prepareColumns()
    {
        $this->column('id', [
            'name'=>'id',
            'label'=>'Id',
            'sortable'=>true,
        ]);

        $this->column('code', [
            'name'=>'code',
            'label'=>'code',
            'sortable'=>true,
        ]);

        $this->column('created_at', [
            'name'=>'created_at',
            'label'=>'Created Date',
            'sortable'=>true,
        ]);
        return $this;
    }


    public function prepareFilters()
    {
        $this->filter('code', [
            'type'  => 'text',
            'label' => 'Code',
        ]);

        $this->filter('created_at', [
            'type'  => 'date',
            'label' => 'Created Date',
        ]);

        return $this;
    }
    
    public function prepareCollection() 
    {
        $apiResource = $this->model(ApiResource::class);
        $query = $apiResource->query();
        if($this->sortColumn() && $this->sortDir()){
             $query->orderBy($this->sortColumn(), $this->sortDir());
        }
        $this->applyFilters($query);
        $this->pager($query);
        return $this;
    }

}