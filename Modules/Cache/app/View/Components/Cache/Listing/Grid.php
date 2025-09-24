<?php
namespace Modules\Cache\View\Components\Cache\Listing;

use Modules\Cache\Models\CacheRegistry;
use Modules\Core\View\Components\Listing\Grid as CoreGrid;
class Grid extends CoreGrid
{

    public function __construct()
    {
        parent::__construct();
        $this->title('Manage Cache');
    }

    public function prepareColumns()
    {
        $this->column('id', [
            'name' => 'id',
            'label' => 'ID',
            'sortable' => true,
        ]);
    
        $this->column('area', [
            'name' => 'area',
            'label' => 'Area',
            'sortable' => true,
        ]);
    
        $this->column('type', [
            'name' => 'type',
            'label' => 'Type',
            'sortable' => true,
        ]);
    
        $this->column('key', [
            'name' => 'key',
            'label' => 'Cache Key',
            'sortable' => true,
        ]);
    
        $this->column('store', [
            'name' => 'store',
            'label' => 'Store',
            'sortable' => true,
        ]);
    
        $this->column('last_generated', [
            'name' => 'last_generated',
            'label' => 'Last Generated',
            'sortable' => true,
        ]);
    
        $this->column('builder_class', [
            'name' => 'builder_class',
            'label' => 'Builder Class',
            'sortable' => true,
        ]);
    
        $this->column('created_at', [
            'name' => 'created_at',
            'label' => 'Created At',
            'sortable' => true,
        ]);
    
        $this->column('updated_at', [
            'name' => 'updated_at',
            'label' => 'Updated At',
            'sortable' => true,
        ]);
    
        return $this;
    }

    public function prepareActions()
    {
        if(canAccess('admin.cache.clear')){
            $this->action('clear', [
                'id' => 'clearBtn',
                'title' => 'Clear',
                'url' => 'admin.cache.clear',
                'class' => 'btn btn-warning btn-sm',
            ]);
        }
    
        return $this;
    }
    

    public function prepareFilters()
    {
        $this->filter('area', [
            'name' => 'area',
            'type' => 'text',
        ]);
    
        $this->filter('type', [
            'name' => 'type',
            'type' => 'text',
        ]);
    
        $this->filter('key', [
            'name' => 'key',
            'type' => 'text',
        ]);
    
        $this->filter('store', [
            'name' => 'store',
            'type' => 'text',
        ]);
    
        $this->filter('last_generated', [
            'name' => 'last_generated',
            'type' => 'date',
        ]);
    
        $this->filter('created_at', [
            'name' => 'created_at',
            'type' => 'date',
        ]);
    
        $this->filter('updated_at', [
            'name' => 'updated_at',
            'type' => 'date',
        ]);
    
        return $this;
    }
    

    
    public function prepareCollection() 
    {
        $admin = $this->model(CacheRegistry::class);
        $query = $admin->query();
        if($this->sortColumn() && $this->sortDir()){
             $query->orderBy($this->sortColumn(), $this->sortDir());
        }
        $this->applyFilters($query);
        $this->pager($query);
        return $this;
    }

    public function prepareButtons()
    {
        if(canAccess('admin.cache.clearAll')){
            $this->button('clear_all', [
                'route' => urlx('admin.cache.clearAll', [], true),
                'label' => 'Clear All',
                'class' => 'btn btn-danger',
            ]);
        }
        return $this;
    }
    
}