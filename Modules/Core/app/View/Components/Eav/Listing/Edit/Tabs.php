<?php

namespace Modules\Core\View\Components\Eav\Listing\Edit;

use Modules\Core\View\Components\Eav\Block;
use Exception;

class Tabs extends Block
{
    protected $template = 'core::listing.edit.tabs';

    protected array $tabs = [];


    protected $tabFormat = [
        'key' => 'null',
        'title' => 'null',
        'tabClassName'=>'null',
        'tabData'      => [],
    ];
    
    protected ?string $activeTabKey = null;

    public function __construct(){
        parent::__construct();
        $this->prepareTabs();
    }
    
    public function prepareTabs(){
        return $this;
    }
    
    public function tab(string $key, array $value = null, bool $reset = false)
    {
        if(is_null($key)){
            return $this;
        }

        if($reset == true){
            if(array_key_exists($key,$this->tabs)){
                unset($this->tabs[$key]);
            }
            return $this;
        }

        if(!is_null($value)){
            $this->tabs[$key] = array_merge($this->tabFormat, $value);
            return $this;
        }

        if(array_key_exists($key,$this->tabs)){
            return $this->tabs[$key];
        }
        return null;
    }

    public function tabs(array $tabs = null, bool $reset = false)
    {
        if($reset == true){
            $this->tabs = [];
            return $this;
        }

        if(!is_null($tabs)){
            foreach($tabs as $key => $tab){
                if(is_array($tab)){
                    $this->tab($key, $tab);
                }
            }
            return $this;
        }
        return $this->tabs;
    }
    
    // ---------------------------
    // Active Tab Management
    // ---------------------------

    public function activeTabKey($key = null)
    {
        if(!is_null($this->tabs)){
            $this->activeTabKey = array_key_first($this->tabs);
        }
        if(request('tab')){
             $this->activeTabKey = request('tab');
        }
        if(array_key_exists($key,$this->tabs)){
            $this->activeTabKey = $key;
        }
        return $this->activeTabKey;
    }

    public function activeTab()
    {
        $tab = $this->tab($this->activeTabKey());
        if (!$tab) return null;
    
        $class = $tab['tabClassName'] ?? null;
        $data  = $tab['tabData'] ?? [];
    
        if (is_string($class) && class_exists($class)) {
            $tab['tabClassName'] = new $class($data);
        }
    
        return $tab['tabClassName'];
    }

    public function getTabComponent($key = null)
    {
        $tab = $this->tab($key ?? $this->activeTabKey());
        if (!$tab) return null;

        $class = $tab['tabClassName'] ?? null;
        $data  = $tab['tabData'] ?? [];

        if (is_string($class) && class_exists($class)) {
            return new $class($data);
        }

        return $class;
    }
}
