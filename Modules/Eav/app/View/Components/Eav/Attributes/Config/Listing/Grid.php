<?php

namespace Modules\Eav\View\Components\Eav\Attributes\Config\Listing;

use Modules\Eav\Models\Eav\Attribute\Config;
use Illuminate\Support\Facades\Request;
use Modules\Core\View\Components\Listing\Grid as CoreGrid;
use Modules\Eav\Models\Eav\Entity\Type;

class Grid extends CoreGrid
{
    public function __construct()
    {
        parent::__construct();
        $title = 'Manage Attribute Config';
        $entityTypeId = request()->get('id');
        if ($entityTypeId) {
            $entityTypeName = Type::where('entity_type_id', $entityTypeId)
                ->value('name');
    
            if ($entityTypeName) {
                $title = "Config: {$entityTypeName}";
            }
        }
    
        $this->title($title);
    }
    

    public function prepareColumns()
    {
        $this->column('entity_type_name', [
            'name' => 'entity_type_name',
            'label' => 'Entity Type',
            'sortable' => true,
        ]);

        $this->column('attribute_name', [
            'name' => 'attribute_name',
            'label' => 'Attribute',
            'sortable' => true,
        ]);

        $this->column('show_in_grid', [
            'name' => 'show_in_grid',
            'label' => 'Show in Grid',
            'columnClassName' => '\Modules\Eav\View\Components\Eav\Attributes\Config\Listing\Grid\Columns\ShowInGrid',
        ]);

        $this->column('default_in_grid', [
            'name' => 'default_in_grid',
            'label' => 'Default in Grid',
            'columnClassName' => '\Modules\Eav\View\Components\Eav\Attributes\Config\Listing\Grid\Columns\DefaultInGrid',
        ]);

        $this->column('is_filterable', [
            'name' => 'is_filterable',
            'label' => 'Filterable',
            'columnClassName' => '\Modules\Eav\View\Components\Eav\Attributes\Config\Listing\Grid\Columns\IsFilterable',
        ]);

        $this->column('is_sortable', [
            'name' => 'is_sortable',
            'label' => 'Sortable',
            'columnClassName' => '\Modules\Eav\View\Components\Eav\Attributes\Config\Listing\Grid\Columns\IsSortable',
        ]);

        return $this;
    }

    public function prepareFilters()
    {
        $this->filter('entity_type_name', [
            'name' => 'entity_type.name',
            'type' => 'text',
        ]);

        $this->filter('attribute_name', [
            'name' => 'attribute.code',
            'type' => 'text',
        ]);

        return $this;
    }

    /**
     * Prepare collection
     */
    public function prepareCollection()
    {
        $this->moduleName = 'Attribute Config';
        $model = $this->model(Config::class);
    
        $query = $model->with([
            'entityType:entity_type_id,name',
            'attribute:attribute_id,code'
        ]);
    
        $entityTypeId = request()->get('id');
        if ($entityTypeId) {
            $query->where('entity_type_id', $entityTypeId);
        }
    
        $allColumns = collect($this->columns())
            ->pluck('name')
            ->filter(fn($col) => $col && !in_array($col, ['entity_type_name', 'attribute_name']))
            ->values()
            ->all();
    
        $hiddenColumns = $this->handleHiddenColumns($model->getKeyName());
        if (!empty($hiddenColumns)) {
            $allColumns = array_diff($allColumns, $hiddenColumns);
        }
    
        $primaryKey = $model->getKeyName();
        $requiredForeignKeys = ['entity_type_id', 'attribute_id'];
        $allColumns = array_unique(array_merge($allColumns, $requiredForeignKeys, [$primaryKey]));
    
        $query->select($allColumns);
    
        $sortColumn = $this->sortColumn();
        $sortDir    = $this->sortDir();
        if ($sortColumn && in_array($sortColumn, $allColumns)) {
            $query->orderBy($sortColumn, $sortDir);
        }
    
        $this->applyFilters($query);
        $this->pager($query);
    
        return $this;
    }
    
    

    public function prepareButtons()
    {
        if (canAccess('admin.eav.attributes.config.save')) {
            $this->button('save', [
                'method' => "document.getElementById('main-form').action='" . route('admin.eav.attributes.config.save') . "'; document.getElementById('main-form').submit();",
                'label' => 'Save Config',
            ]);
        }
    
        return $this;
    }
    
    
}
