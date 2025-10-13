<?php
namespace Modules\Admin\View\Components\Resource\Listing;

use Modules\Admin\Models\Resource;
use Modules\Core\View\Components\Listing\Grid as CoreGrid;
class Grid extends CoreGrid
{
    public function __construct()
    {
        parent::__construct();
        $this->title('Manage Resources');
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
            'label'=>'Code',
            'sortable'=>true,
        ]);

        $this->column('description', [
            'name'=>'description',
            'label'=>'Description',
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
            'name' => 'code',
        ]);

        $this->filter('description', [
            'type'  => 'text',
            'name' => 'description',
        ]);

        $this->filter('created_at', [
            'type'  => 'date',
            'name' => 'created_at',
        ]);

        return $this;
    }
    
    public function prepareCollection() 
    {
        $this->gridKey = 'Admin_Resource';
        $resource = $this->model(Resource::class);
        $query = $resource->query();
        if($this->sortColumn() && $this->sortDir()){
             $query->orderBy($this->sortColumn(), $this->sortDir());
        }
        $this->applyFilters($query);
        $hiddenColumns = $this->handleHiddenColumns($resource->getKeyName());
        if (!empty($hiddenColumns)) {
            $allColumns = array_values(array_diff(array_keys($this->columns()), ['mass_ids']));
            $visibleColumns = array_diff($allColumns, $hiddenColumns);
            $query->select($visibleColumns);
        }
        $this->pager($query);
        return $this;
    }
}