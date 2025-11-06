<?php
namespace Modules\Translation\View\Components\Translation\Listing;

use Modules\Translation\Models\Translation;
use Modules\Core\View\Components\Listing\Grid as CoreGrid;
use Illuminate\Support\Collection;
use Nwidart\Modules\Facades\Module;
class Grid extends CoreGrid
{

    public function __construct()
    {
        parent::__construct();
        $this->title('Manage Translations');
    }

    public function prepareColumns()
    {
        if (canAccess('admin.translation.massDelete')) {
            $this->column('mass_ids', [
                'name' => 'id',
                'label' => '',
                'columnClassName' => '\Modules\Core\View\Components\Listing\Grid\Columns\MassIds',
            ]);
        }
    
        $this->column('id', [
            'name' => 'id',
            'label' => 'ID',
            'sortable' => true,
        ]);
    
        $this->column('module', [
            'name' => 'module',
            'label' => 'Module',
            'sortable' => true,
        ]);
    
        $this->column('locale', [
            'name' => 'locale',
            'label' => 'Locale',
            'sortable' => true,
        ]);
    
        $this->column('group', [
            'name' => 'group',
            'label' => 'Group',
            'sortable' => true,
        ]);
    
        $this->column('key', [
            'name' => 'key',
            'label' => 'Key',
            'sortable' => true,
        ]);
    
        $this->column('value', [
            'name' => 'value',
            'label' => 'Value',
            'sortable' => false,
        ]);
    
        $this->column('created_at', [
            'name' => 'created_at',
            'label' => 'Created Date',
            'sortable' => true,
        ]);
        return $this;
    }
    

    public function prepareActions()
    {
        if(canAccess('admin.translation.edit')){
            $this->action('edit' , [
                'id' => 'editBtn',
                'title' => 'Edit',
                'url' => 'admin.translation.edit'
            ]);
        }
        if(canAccess('admin.translation.delete')){
            $this->action('delete' , [
                'id' => 'deleteBtn',
                'title' => 'Delete',
                'url' => 'admin.translation.delete'
            ]);
        }
        return $this;
    }

    public function prepareFilters()
    {
        $this->filter('module', [
            'name' => 'module',
            'type' => 'select',
            'options' => collect(Module::all())
                ->mapWithKeys(function ($mod) {
                    return [$mod->getName() => $mod->getName()];
                })
                ->toArray(),
        ]);
    
        $this->filter('locale', [
            'name' => 'locale',
            'type' => 'select',
            'options' => [
                'en' => 'English',
                'fr' => 'French',
                'es' => 'Spanish',
            ],
        ]);
    
        $this->filter('group', [
            'name' => 'group',
            'type' => 'text',
        ]);
    
        $this->filter('key', [
            'name' => 'key',
            'type' => 'text',
        ]);
    
        $this->filter('value', [
            'name' => 'value',
            'type' => 'text',
        ]);
    
        $this->filter('created_at', [
            'name' => 'created_at',
            'type' => 'date',
        ]);
    
        return $this;
    }
    

    public function prepareMassActions(){
        if(canAccess('admin.translation.massDelete')){
            parent::prepareMassActions();
            $this->massAction('delete', [
                'value'=>'mass_delete',
                'label' =>'Delete Selected',
                'url' => 'admin.translation.massDelete',
            ]);
        }

        if (canAccess('admin.translation.export')) {
            $this->massAction('export', [
                'value' => 'mass_export',
                'label' => 'Export',
                'url' => 'admin.translation.export',
            ]);
        }
    }
    
    public function prepareCollection() 
    {
        $this->gridKey = 'Translation';
        $admin = $this->model(Translation::class);
        $query = $admin->query();
        if($this->sortColumn() && $this->sortDir()){
             $query->orderBy($this->sortColumn(), $this->sortDir());
        }
        $this->applyFilters($query);
        $hiddenColumns = $this->handleHiddenColumns($admin->getKeyName());
        if (!empty($hiddenColumns)) {
            $allColumns = array_values(array_diff(array_keys($this->columns()), ['mass_ids']));
            $visibleColumns = array_diff($allColumns, $hiddenColumns);
            $query->select($visibleColumns);
        }
        $this->pager($query);
        return $this;
    }

    public function prepareButtons()
    {
        if(canAccess('admin.translation.add')){
            $this->button('add', [
                'route' =>urlx('admin.translation.add',[],true),
                'label' => 'Add Translation',
            ]);
        }
        if(canAccess('admin.translation.addLocale')){
            $this->button('addLocale', [
                'route' =>urlx('admin.translation.addLocale',[],true),
                'label' => 'Add Language',
            ]);
        }
        return $this;
    }
}