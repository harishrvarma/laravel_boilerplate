<?php

namespace Modules\Product\View\Components\Product\Entity\Listing;

use Modules\Product\Models\Product\Entity;
use Modules\Product\Models\Product\Value;
use Modules\Eav\Models\Eav\Attribute;
use Modules\Core\View\Components\Eav\Listing\Grid as CoreGrid;
use Modules\Eav\Models\Eav\Entity\Type;
use Modules\Translation\Models\TranslationLocale;
use Illuminate\Support\Facades\App;

class Grid extends CoreGrid
{
    protected $entityTypeId = null;

    public function __construct()
    {
        parent::__construct();
        $this->title(__('Product::messages.manage_products'));
    }

    public function prepareColumns()
    {
        if (canAccess('admin.product.entity.massDelete')) {
            $this->column('mass_ids', [
                'name' => 'entity_id',
                'label' => '',
                'columnClassName' => '\Modules\Core\View\Components\Listing\Grid\Columns\MassIds',
            ]);
        }
    
        $this->column('entity_id', [
            'name' => 'entity_id',
            'label' => 'Id',
            'sortable' => true,
        ]);
    
        $attributes = $this->getAttributes();

        foreach ($attributes as $attr) {
            if ($attr->config?->show_in_grid) {
                $effectiveLangId = ((int) $attr->lang_type === 1) ? config_locale_id() : current_locale_id();
                $translation = $attr->translations->firstWhere('lang_id', $effectiveLangId);
                $label = $translation?->display_name ?? $attr->code;
                $this->column($attr->code, [
                    'name' => $attr->code,
                    'label' => $label,
                    'sortable' => (bool) $attr->config?->is_sortable,
                ]);
            }
        }
    
        $this->column('created_at', ['name' => 'created_at', 'label' => 'Created At', 'sortable' => true]);
        $this->column('updated_at', ['name' => 'updated_at', 'label' => 'Updated At', 'sortable' => true]);

        return $this;
    }

    public function prepareCollection()
    {
        $this->gridKey = 'Entity';
        $entityModel = $this->model(Entity::class);
    
        $query = $entityModel->newQuery()->from('product_entity as e');
    
        $baseColumns = ['entity_id', 'created_at', 'updated_at'];
        $attributes = $this->getAttributes();
    
        $allAttributeCodes = $attributes->filter(fn($attr) => $attr->config?->show_in_grid)
            ->pluck('code')
            ->toArray();
    
        $hiddenColumns = $this->handleHiddenColumns('entity_id', $attributes);
        $visibleColumns = array_diff(array_merge($baseColumns, $allAttributeCodes), $hiddenColumns);
    
        $selects = [];
        foreach ($baseColumns as $col) {
            if (in_array($col, $visibleColumns)) {
                $selects[] = "e.$col as $col";
            }
        }

        $query->selectRaw(implode(', ', $selects));
    
        $visibleAttributes = $attributes
            ->filter(fn($attr) => $attr->config?->show_in_grid && in_array($attr->code, $visibleColumns))
            ->all();

        $query = $entityModel->joinAttr($query,$visibleAttributes, 'left');
        
        if ($this->sortColumn() && $this->sortDir()) {
            $sortColumn = $this->sortColumn();
            if (in_array($sortColumn, $visibleColumns)) {
                $sortColumn = in_array($sortColumn, $baseColumns) ? "e.$sortColumn" : "$sortColumn.value";
                $query->orderBy($sortColumn, $this->sortDir());
            }
        }
    
        $this->applyFilters($query);
        $this->pager($query);
    
        $results = $query->get()->transform(fn($row) => tap($row, fn($r) => $r->id = $r->entity_id));
        $this->rows = $results;
    
        return $this;
    }
    
    public function prepareActions()
    {
        if (canAccess('admin.product.entity.edit')) {
            $this->action('edit', [
                'id' => 'editBtn',
                'title' => 'Edit',
                'url' => 'admin.product.entity.edit',
            ]);
        }

        if (canAccess('admin.product.entity.delete')) {
            $this->action('delete', [
                'id' => 'deleteBtn',
                'title' => 'Delete',
                'url' => 'admin.product.entity.delete',
            ]);
        }

        return $this;
    }

    public function prepareButtons()
    {
        if (canAccess('admin.product.entity.add')) {
            $this->button('add', [
                'route' => urlx('admin.product.entity.add', [], true),
                'label' => 'Add Product',
            ]);
        }
        return $this;
    }

    public function prepareFilters()
    {
        $this->filter('created_at', [
            'name' => 'created_at',
            'column' => 'e.created_at',
            'type' => 'date',
        ]);
        
        $this->filter('updated_at', [
            'name' => 'updated_at',
            'column' => 'e.updated_at',
            'type' => 'date',
        ]);
    
        $entityTypeId = Type::where('code', 'product')->value('entity_type_id');

        $attributes = Attribute::where('entity_type_id', $entityTypeId)
            ->with('config', 'translations')
            ->orderBy('position')
            ->get();

        foreach ($attributes as $attr) {
            if ($attr->config?->show_in_grid && $attr->config?->is_filterable) {
                $label = $attr->translations->first()?->display_name ?? $attr->code;
                $alias = $attr->code;

                $this->filter($alias, [
                    'name' => $alias,
                    'column' => "{$alias}.value",
                    'type' => $attr->data_type,
                    'label' => $label,
                ]);
            }
        }
        return $this;
    }
    
    public function prepareMassActions()
    {
        if (canAccess('admin.product.entity.massDelete')) {
            parent::prepareMassActions();
            $this->massAction('delete', [
                'value' => 'mass_delete',
                'label' => 'Delete Selected',
                'url' => 'admin.product.entity.massDelete',
            ]);
        }

        if (canAccess('admin.product.entity.export')) {
            $this->massAction('export', [
                'value' => 'mass_export',
                'label' => 'Export',
                'url' => 'admin.product.entity.export',
            ]);
        }

        return $this;
    }

    protected function getAttributes()
    {
        if (!isset($this->attributes)) {
            $entityTypeId = Type::where('code', 'product')->value('entity_type_id');
            $this->attributes = Attribute::where('entity_type_id', $entityTypeId)
                ->with(['translations', 'config'])
                ->orderBy('position')
                ->get();
        }

        return $this->attributes;
    }

}
