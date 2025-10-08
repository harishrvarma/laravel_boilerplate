<?php
namespace Modules\Core\View\Components\Listing;

use Exception;
use Modules\Core\View\Components\Block;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\View\Components\Listing\Grid\Pagination;

class Grid extends Block
{
    // Singular
    protected $perPage = 5;
    protected $page = 1;
    protected $sortColumn = null;
    protected $sortDir = 'asc';
    protected $totalCount = 0;
    protected $allowedPagination = true;
 
    protected $title = null;
    // Plural
    protected $actions = [];
    protected $actionFormat = [];

    protected $columns = [];
    protected $columnFormat = [
        'name'=> null,
        'label'=> null,
        'columnClassName'=> null,
    ];

    protected $filters = [];
    protected $filterFormat = [
        'name'=> null,
        'type'=> 'text',
        'filterClassName'=> '\Modules\Core\View\Components\Listing\Grid\Filter',
        'template' => null 
    ];

    protected $massActions = [];
    protected $massActionFormat = [];

    protected $rows = [];
    protected $paginator;

    protected $rendererObject = null;

    protected $buttons = [];

    protected $buttonFormat = [
        'label'=>null,
        'route'=>null,
    ];

    protected $template = 'core::listing.grid';

    public function __construct()
    {
        parent::__construct();
        $this->sortColumn();
        $this->sortDir();
        $this->prepareColumns();
        $this->prepareFilters();
        $this->prepareCollection();
        $this->prepareActions();
        $this->prepareMassActions();
        $this->prepareButtons();
    }

    public function prepareColumns()
    {
        return $this;
    }

    public function allowedPagination(bool $allowedPagination = null){
        if(!is_null($allowedPagination)){
            $this->allowedPagination = $allowedPagination;
        }
        return $this->allowedPagination;
    }


    public function prepareMassActions()
    {
         $this->massAction('none', [
            'value'=>'none',
            'label' =>'',
            'url' => null,
        ]);
        
        return $this;
    }

    public function totalCount()
    {
        return $this->totalCount;
    }


    public function prepareActions()
    {
        return $this;
    }

    public function prepareFilters()
    {
        return $this;
    }
    
    public function prepareCollection() 
    {
        return $this;
    }

    public function pager($query = null)
    {
        if ($query) {
            $this->totalCount = $query->count();
    
            $this->rows = $query
                ->forPage($this->page(), $this->perPage())
                ->get();
    
            $selectAll = (int) request()->get('selectAll', 0);
            
            if ($selectAll === 1) {
                foreach ($this->rows as $row) {
                    $row->selected = true;
                }
            } elseif ($selectAll === 0) {
                foreach ($this->rows as $row) {
                    $row->selected = false;
                }
            }
    
            $this->paginator = new LengthAwarePaginator(
                $this->rows,
                $this->totalCount,
                $this->perPage(),
                $this->page(),
                ['path' => request()->url(), 'query' => request()->query()]
            );
        }
    
        return new Pagination($this->paginator, [], $this->perPage(), $this);
    }
    

    protected function applyFilters($query)
    {
        $filters = request()->post('filter', []);
    
        if (!$filters) {
            return $query;
        }
    

        foreach ($this->filters() as $colName => $filter) {
            $type  = $filter['type'];
            $name = $filter['name'];
    
            $value = $filters[$name] ?? null;
    
            if (($value === null || $value === '' || $value === []) && $value !== "0" && $value !== 0) {
                continue;
            }
    
            switch ($type) {
                case 'text':
                case 'textarea':
                    $query->where($colName, 'like', '%' . $value . '%');
                    break;
    
                case 'select':
                case 'boolean':
                case 'number':
                    $query->where($colName, $value);
                    break;
    
                case 'date':
                    $query->whereDate($colName, $value);
                    break;
    
                case 'multiselect':
                    $query->whereIn($colName, (array) $value);
                    break;
    
                case 'range':
                    $min = $filters[$colName]['min'] ?? null;
                    $max = $filters[$colName]['max'] ?? null;
                    if ($min !== null && $min !== '') {
                        $query->where($colName, '>=', $min);
                    }
                    if ($max !== null && $max !== '') {
                        $query->where($colName, '<=', $max);
                    }
                    break;
    
                default:
                    $query->where($colName, $value);
            }
        }
    
        return $query;
    }
    


    public function filter(string $key, array $value = null, bool $reset = false)
    {
        if(is_null($key)){
            return $this;
        }

        if($reset == true){
            if(array_key_exists($key,$this->filters)){
                unset($this->filters[$key]);
            }
            return $this;
        }

        if(!is_null($value)){
            $this->filters[$key] = array_merge($this->filterFormat, $value);
            return $this;
        }
        if(array_key_exists($key,$this->filters)){
            return $this->filters[$key];
        }
        return null;
    }

    public function filters(array $filters = null, bool $reset = false)
    {
        if($reset == true){
            $this->filters = [];
            return $this;
        }

        if(!is_null($filters)){
            foreach($filters as $key => $filter){
                if(is_array($filter)){
                    $this->filter($key, $filter);
                }
            }
            return $this;
        }
        return $this->filters;
    }

    public function perPage()
    {
        $perPage = (int)request('per_page',$this->perPage);

        if ($perPage <= 0) {
            $perPage = $this->perPage;
        }
        $this->perPage = $perPage;
        return $this->perPage;

    }

    public function page()
    {
        $page = (int)request('page',$this->page);

        if ( $page <= 0) {
            $page = $this->page;
        }
        $this->page = $page;
        return $this->page;

    }

    public function sortColumn()
    {
        $column = request('sortcolumn',null);
        
        if ($column !== null && !is_string($column)) {
            throw new \InvalidArgumentException("SortColumn must be a string or null.");
        }

        $this->sortColumn = $column;
        
        return $this->sortColumn;

    }
    
    public function sortDir()
    {
        $sortDir = request('sortdir','asc');
        
        if($sortDir){
            $sortDir = strtolower($sortDir);
        }

        if (!in_array($sortDir, ['asc', 'desc'])) {
            throw new \InvalidArgumentException("SortDir must be 'asc' or 'desc'.");
        }
        $this->sortDir = $sortDir;
        return $this->sortDir;
    }

    /* --------------------- Plural Getters/Setters with Validation --------------------- */

    public function column(string $key, array $value = null, bool $reset = false)
    {
        if(is_null($key)){
            return $this;
        }

        if($reset == true){
            if(array_key_exists($key,$this->columns)){
                unset($this->columns[$key]);
            }
            return $this;
        }

        if(!is_null($value)){
            $this->columns[$key] = array_merge($this->columnFormat, $value);
            return $this;
        }

        if(array_key_exists($key,$this->columns)){
            return $this->columns[$key];
        }
        return null;
    }

    public function columns(array $columns = null, bool $reset = false)
    {
        if($reset == true){
            $this->columns = [];
            return $this;
        }

        if(!is_null($columns)){
            foreach($columns as $key => $column){
                if(is_array($column)){
                    $this->column($key, $column);
                }
            }
            return $this;
        }
        return $this->columns;
    }
    // Rows
    public function rows(array $rows = null)
    {
        if(!is_null($rows)){
            if (!is_array($rows)) {
                throw new \InvalidArgumentException("Rows must be an array.");
            }
            $this->rows = $rows;
            return $this;
        }
        return $this->rows;
    }

    public function action(string $key, array $value = null, bool $reset = false)
    {
        if(is_null($key)){
            return $this;
        }

        if($reset == true){
            if(array_key_exists($key,$this->actions)){
                unset($this->actions[$key]);
            }
            return $this;
        }

        if(!is_null($value)){
            $this->actions[$key] = array_merge($this->actionFormat, $value);
            return $this;
        }

        if(array_key_exists($key,$this->actions)){
            return $this->actions[$key];
        }
        return null;
    }

    public function actions(array $actions = null, bool $reset = false)
    {
        if($reset == true){
            $this->actions = [];
            return $this;
        }

        if(!is_null($actions)){
            foreach($actions as $key => $action){
                if(is_array($action)){
                    $this->action($key, $action);
                }
            }
            return $this;
        }
        return $this->actions;
    }

    public function getButtonHtml($action, $row)
    {
        $url = $action['url'];
        $icon = $action['icon'] ?? '';
        $title = $action['title'];
        $class = $action['class'] ?? 'btn btn-sm btn-secondary';
        return "<a href=\"" . $this->urlx($url, ['id' => $row->getKey()]) . "\" class=\"$class\">$icon $title</a>";
    }


    public function url(array $overrides = [], string $baseUrl = null): string
    {
        $params = request()->query();

        $resetPageKeys = ['sortcolumn', 'sortdir', 'filter', 'per_page'];

        foreach ($resetPageKeys as $key) {
            if (array_key_exists($key, $overrides)) {
                // Reset page to 1 when these keys are overridden
                $params['page'] = 1;
                break;
            }
        }

        $params = array_merge($params, $overrides);

        $params = array_filter($params, fn($v) => $v !== null && $v !== '');

        $baseUrl = $baseUrl ?? url()->current();

        return $baseUrl . '?' . http_build_query($params);
    }

    public function massAction(string $key, array $value = null, bool $reset = false)
    {
        if(is_null($key)){
            return $this;
        }

        if($reset == true){
            if(array_key_exists($key,$this->massActions)){
                unset($this->massActions[$key]);
            }
            return $this;
        }

        if(!is_null($value)){
            $this->massActions[$key] = array_merge($this->massActionFormat, $value);
            return $this;
        }

        if(array_key_exists($key,$this->massActions)){
            return $this->massActions[$key];
        }
        return null;
    }

    public function massActions(array $massActions = null, bool $reset = false)
    {
        if($reset == true){
            $this->massActions = [];
            return $this;
        }

        if(!is_null($massActions)){
            foreach($massActions as $key => $massAction){
                if(is_array($massAction)){
                    $this->massAction($key, $massAction);
                }
            }
            return $this;
        }
        return $this->massActions;
    }

    public function getRendererBlock($column,$row) {
        if(!$this->rendererObject){
            $this->rendererObject = $this->block($column['columnClassName']);
        }
        return $this->rendererObject->row($row)->column($column);
    }

    public function getFilterBlock($column,$filter) {
        
        if(empty($column) || empty($filter)){
            throw new Exception("Invalid column or filter");
        }
        $block = $this->block($filter['filterClassName'])->filter($filter)->column($column);
        return $block;

    }


    public function prepareButtons(){
        return $this;
    }

    public function button(string $key, array $value = null, bool $reset = false)
    {
        if(is_null($key)){
            return $this;
        }

        if($reset == true){
            if(array_key_exists($key,$this->buttons)){
                unset($this->buttons[$key]);
            }
            return $this;
        }

        if(!is_null($value)){
            $this->buttons[$key] = array_merge($this->buttonFormat, $value);
            return $this;
        }

        if(array_key_exists($key,$this->buttons)){
            return $this->buttons[$key];
        }
        return null;
    }

    public function buttons(array $buttons = null, bool $reset = false)
    {
        if($reset == true){
            $this->buttons = [];
            return $this;
        }

        if(!is_null($buttons)){
            foreach($buttons as $key => $button){
                if(is_array($button)){
                    $this->button($key, $button);
                }
            }
            return $this;
        }
        return $this->buttons;
    }

    public function title(string $title = null){
        if(!is_null($title)){
            $this->title = $title;
            return $this;
        }
        return $this->title;
    }
}